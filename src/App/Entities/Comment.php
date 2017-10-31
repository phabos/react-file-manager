<?php

namespace App\Entities;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    const MAX_RESULT = 10;
    protected $table = 'comments';
    protected $fillable = ['content', 'created', 'updated', 'parend_id'];
    public $timestamps = false;

}
