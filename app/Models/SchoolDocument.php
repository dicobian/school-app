<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolDocument extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path'
    ];
}
