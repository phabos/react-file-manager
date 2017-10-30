<?php

namespace App\Entities;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = ['title', 'path', 'uploaded'];
    public $timestamps = false;

}
