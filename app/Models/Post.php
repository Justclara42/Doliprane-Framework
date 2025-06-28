<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts'; // facultatif si le nom correspond
    protected $fillable = ['title', 'slug', 'content'];
}
