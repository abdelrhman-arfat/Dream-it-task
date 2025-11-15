<?php

namespace Tests\Feature\API;

use App\Http\Resources\BooksResource;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure 'author' role exists
        Role::firstOrCreate([
            'name' => 'author',
            'guard_name' => 'web',
        ]);
    }

    public function test_authenticated_user_can_crud_books()
    {
        $user = User::factory()->create();
        $user->assignRole('author');

        Sanctum::actingAs($user, ['*']);

        // Create book
        $payload = [
            'title_en' => 'New Book',
            'title_ar' => 'كتاب جديد',
            'description_en' => 'New description',
            'description_ar' => 'وصف جديد',
        ];

        $createResponse = $this->postJson('/api/books', $payload);

        $createResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'author' => [
                        'id',
                        'name',
                        'bio'
                    ],
                    'created_at',
                    'updated_at'
                ]
            ]);

        // Check database
        $this->assertDatabaseHas('books', [
            'title_en' => 'New Book',
            'author_id' => $user->id,
        ]);

        $bookId = $createResponse->json('data.id');

        // Update book
        $updatePayload = [
            'title_en' => 'Updated Book',
            'title_ar' => 'كتاب محدث',
            'description_en' => 'Updated description',
            'description_ar' => 'وصف محدث',
        ];

        $updateResponse = $this->putJson("/api/books/{$bookId}", $updatePayload);

        $updateResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $bookId,
                    'title' => 'Updated Book',
                    'description' => 'Updated description',
                ]
            ]);

        $this->assertDatabaseHas('books', [
            'id' => $bookId,
            'title_en' => 'Updated Book',
        ]);

        // Delete book
        $deleteResponse = $this->deleteJson("/api/books/{$bookId}");
        $deleteResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => null
            ]);

        $this->assertDatabaseMissing('books', [
            'id' => $bookId,
        ]);
    }

    public function test_user_cannot_update_or_delete_others_books()
    {
        $user = User::factory()->create();
        $user->assignRole('author');
        Sanctum::actingAs($user, ['*']);

        $otherUser = User::factory()->create();
        $otherUser->assignRole('author');

        $book = Book::factory()->create([
            'author_id' => $otherUser->id,
        ]);

        $updateResponse = $this->putJson("/api/books/{$book->id}", [
            'title_en' => 'Hack Book',
            'title_ar' => 'اختراق',
            'description_en' => 'Hack desc',
            'description_ar' => 'وصف اختراق',
        ]);

        $updateResponse->assertStatus(404)
            ->assertJson(['success' => false]);

        $deleteResponse = $this->deleteJson("/api/books/{$book->id}");
        $deleteResponse->assertStatus(404)
            ->assertJson(['success' => false]);
    }

    public function test_list_books_returns_books_resource()
    {
        $author = User::factory()->create();
        $author->assignRole('author');
        Sanctum::actingAs($author, ['*']);

        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'author' => [
                            'id',
                            'name',
                            'bio',
                        ],
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    public function test_show_single_book_returns_books_resource()
    {
        $author = User::factory()->create();
        $author->assignRole('author');
        Sanctum::actingAs($author, ['*']);
        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'author' => [
                        'id',
                        'name',
                        'bio',
                    ],
                    'created_at',
                    'updated_at',
                ]
            ]);
    }
}
