<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'file_url',
    ];
    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
