<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BooksResource;
use App\Models\Book;
use App\Triats\ResponseTrait;
use App\Triats\LoggerTrait;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BooksController extends Controller
{
    use ResponseTrait, LoggerTrait;

    /**
     * List all books of authenticated user
     */
    public function index(Request $request)
    {
        try {
            $books = $request->user()->books()->get();
            return $this->successResponse($books, __('messages.books.listed'));
        } catch (\Exception $e) {
            $this->logger('Failed to list books');
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Get all books with author info (paginated)
     */
    public function allBooks(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $books = Book::with('author:id,name_en,name_ar,email')
                ->paginate($perPage);
            $resource = BooksResource::collection($books);
            return $this->paginationResponse($resource, $books, __('messages.books.listed'));
        } catch (\Exception $e) {
            $this->logger('Failed to get all books');
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Show a single book of authenticated user
     */
    public function show(Request $request, $id)
    {
        try {
            $book = Book::with('author')->findOrFail($id);
            $res = new BooksResource($book);
            return $this->successResponse($res, __('messages.books.found'));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(__('messages.books.not_found'), 404);
        } catch (\Exception $e) {
            $this->logger('Failed to show book');
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a new book for authenticated user
     */
    public function store(StoreBookRequest $request)
    {
        try {
            $user  = $request->user();

            if (!$user->hasRole("author")) return $this->errorResponse(__('messages.auth.forbidden'), 403);

            $validated = $request->validated();
            $book = $user->books()->create($validated);
            $res = new BooksResource($book);
            return $this->successResponse($res, __('messages.books.created'));
        } catch (\Exception $e) {
            $this->logger('Failed to create book', [
                "message" => $e->getMessage()
            ]);
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Update a book of authenticated user
     */
    public function update(UpdateBookRequest $request, $id)
    {
        try {
            $book = $request->user()->books()->findOrFail($id);
            $validated = $request->validated();
            $book->update($validated);
            $res = new BooksResource($book);

            return $this->successResponse($res, __('messages.books.updated'));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(__('messages.books.not_found'), 404);
        } catch (\Exception $e) {
            $this->logger('Failed to update book', [
                "message" => $e->getMessage()
            ]);
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete a book of authenticated user
     */
    public function destroy(Request $request, $id)
    {
        try {
            $book = $request->user()->books()->findOrFail($id);
            $book->delete();
            return $this->successResponse(null, __('messages.books.deleted'));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(__('messages.books.not_found'), 404);
        } catch (\Exception $e) {
            $this->logger('Failed to delete book', [
                "message" => $e->getMessage()
            ]);
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
