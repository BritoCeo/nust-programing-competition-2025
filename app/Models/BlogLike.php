<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_post_id',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the blog post that was liked
     */
    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }

    /**
     * Get the user that liked the post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user has liked a specific post
     */
    public static function hasLiked(int $userId, int $blogPostId): bool
    {
        return self::where('user_id', $userId)
                   ->where('blog_post_id', $blogPostId)
                   ->exists();
    }

    /**
     * Toggle like for a user and post
     */
    public static function toggleLike(int $userId, int $blogPostId): bool
    {
        $like = self::where('user_id', $userId)
                    ->where('blog_post_id', $blogPostId)
                    ->first();

        if ($like) {
            $like->delete();
            return false; // Unliked
        } else {
            self::create([
                'user_id' => $userId,
                'blog_post_id' => $blogPostId
            ]);
            return true; // Liked
        }
    }

    /**
     * Get like count for a post
     */
    public static function getLikeCount(int $blogPostId): int
    {
        return self::where('blog_post_id', $blogPostId)->count();
    }
}
