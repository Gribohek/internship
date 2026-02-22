<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content'];
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
        return $this->belongsToMany(
            User::class,
            'mentions',
            'post_id',
            'mentioned_user_id'
        );
    }
    public function scopeWithFollowStatus(
        Builder $query,
        int $currentUserId
    ): Builder {
        return $query->addSelect([
            'is_following' => function ($subQuery) use ($currentUserId) {
                $subQuery->selectRaw('EXISTS (
                        SELECT 1
                        FROM follows
                        WHERE follows.following_id = posts.user_id
                        AND follows.follower_id = ?
                    )', [$currentUserId]);
            }
        ]);
    }

    public function scopeFeedForUser(
        Builder $query,
        int $userId
    ): Builder {
        return $query->where(function ($q) use ($userId) {
            $q->whereExists(function ($sub) use ($userId) {
                $sub->selectRaw(1)
                    ->from('follows')
                    ->whereColumn('follows.following_id', 'posts.user_id')
                    ->where('follows.follower_id', $userId);
            })
            ->orWhere('posts.user_id', $userId)
            ->orWhereExists(function ($sub) use ($userId) {
                $sub->selectRaw(1)
                    ->from('mentions')
                    ->whereColumn('mentions.post_id', 'posts.id')
                    ->where('mentions.mentioned_user_id', $userId);
            });

        });
    }
}
