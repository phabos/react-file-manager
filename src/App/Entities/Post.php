<?php

namespace App\Entities;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    protected $fillable = ['name', 'content', 'status'];

}
