<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbTestData extends Model
{
    use HasFactory;

    protected $table = 'ab_test_data'; // Explicitly set the table name

    protected $fillable = [
        'test_id',
        'user_session_id',
        'version',
        'page_views',
        'conversions',
        'current_url',
    ];

    public $timestamps = true; // Laravel will automatically handle created_at & updated_at
}
