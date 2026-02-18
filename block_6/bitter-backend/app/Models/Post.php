<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content'];
    
    protected $with = ['user', 'hashtags'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class);
    }

    public function mentions()
    {
        return $this->belongsToMany(User::class, 'mentions', 'post_id', 'mentioned_user_id');
    }
}