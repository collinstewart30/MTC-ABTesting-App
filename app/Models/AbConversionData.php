<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbConversionData extends Model
{
    use HasFactory;

    protected $table = 'ab_test_conversions'; // Explicitly set the table name

    protected $fillable = [
        'user_session_id',
        'conversion_type',
    ];

    public $timestamps = true; // Laravel will automatically handle created_at & updated_at
}
