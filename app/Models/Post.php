<?php

namespace App\Models;

use Corcel\Model\Post as Corcel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Post extends Model
{
    protected $connection = 'wordpress';
    use HasFactory;

    protected $postType = 'post';

    


    
}
