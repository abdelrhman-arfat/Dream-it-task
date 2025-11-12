<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Logs extends Model
{
    use HasFactory;

    protected $table = 'logs';

    protected $fillable = ['user_id', 'endpoint', 'method', 'created_at'];

    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($log) {
            if (empty($log->created_at)) {
                $log->created_at = now();
            }
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
