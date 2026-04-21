<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['title', 'body', 'image_url','user_id'])]
#[Hidden(['user_id'])]

class Post extends Model
{
   
protected $with = ['user']; // eager load user relationship
   protected function casts(): array
    {
        return [
            'user_id' => 'integer',
        ];
    }

    //get with user who created the post
    public function user(){
        return $this->belongsTo(User::class);
    }
    

}
