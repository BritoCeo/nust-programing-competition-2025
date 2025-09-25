<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogComment;
use App\Models\BlogLike;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Get all blog posts
     */
    public function index(Request $request): JsonResponse
    {
        $query = BlogPost::with(['author', 'comments', 'likes'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc');

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get single blog post
     */
    public function show(BlogPost $blogPost): JsonResponse
    {
        $blogPost->load(['author', 'comments.user', 'likes.user']);
        
        // Increment view count
        $blogPost->increment('views');

        return response()->json([
            'success' => true,
            'data' => $blogPost
        ]);
    }

    /**
     * Create new blog post
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|string|in:general,health_tips,disease_info,treatment,prevention,mental_health',
            'tags' => 'nullable|array',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:draft,published'
        ]);

        $data = $request->all();
        $data['author_id'] = Auth::id();
        $data['status'] = $data['status'] ?? 'draft';

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        $blogPost = BlogPost::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Blog post created successfully',
            'data' => $blogPost->load('author')
        ], 201);
    }

    /**
     * Update blog post
     */
    public function update(Request $request, BlogPost $blogPost): JsonResponse
    {
        // Check if user can edit this post
        if ($blogPost->author_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to edit this post'
            ], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'sometimes|required|string|in:general,health_tips,disease_info,treatment,prevention,mental_health',
            'tags' => 'nullable|array',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:draft,published'
        ]);

        $data = $request->all();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blogPost->featured_image) {
                Storage::disk('public')->delete($blogPost->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        $blogPost->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Blog post updated successfully',
            'data' => $blogPost->load('author')
        ]);
    }

    /**
     * Delete blog post
     */
    public function destroy(BlogPost $blogPost): JsonResponse
    {
        // Check if user can delete this post
        if ($blogPost->author_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this post'
            ], 403);
        }

        // Delete featured image
        if ($blogPost->featured_image) {
            Storage::disk('public')->delete($blogPost->featured_image);
        }

        $blogPost->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog post deleted successfully'
        ]);
    }

    /**
     * Add comment to blog post
     */
    public function addComment(Request $request, BlogPost $blogPost): JsonResponse
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = BlogComment::create([
            'blog_post_id' => $blogPost->id,
            'user_id' => Auth::id(),
            'content' => $request->input('content')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment->load('user')
        ], 201);
    }

    /**
     * Like/unlike blog post
     */
    public function toggleLike(BlogPost $blogPost): JsonResponse
    {
        $userId = Auth::id();
        
        $existingLike = BlogLike::where('blog_post_id', $blogPost->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            BlogLike::create([
                'blog_post_id' => $blogPost->id,
                'user_id' => $userId
            ]);
            $liked = true;
        }

        $likeCount = BlogLike::where('blog_post_id', $blogPost->id)->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $likeCount
        ]);
    }

    /**
     * Get blog categories
     */
    public function getCategories(): JsonResponse
    {
        $categories = [
            'general' => 'General Health',
            'health_tips' => 'Health Tips',
            'disease_info' => 'Disease Information',
            'treatment' => 'Treatment',
            'prevention' => 'Prevention',
            'mental_health' => 'Mental Health'
        ];

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get popular blog posts
     */
    public function getPopular(): JsonResponse
    {
        $popularPosts = BlogPost::with(['author', 'likes'])
            ->where('status', 'published')
            ->orderBy('views', 'desc')
            ->orderBy('likes_count', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $popularPosts
        ]);
    }

    /**
     * Get recent blog posts
     */
    public function getRecent(): JsonResponse
    {
        $recentPosts = BlogPost::with(['author', 'likes'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $recentPosts
        ]);
    }

    /**
     * Get blog statistics
     */
    public function getStatistics(): JsonResponse
    {
        $stats = [
            'total_posts' => BlogPost::where('status', 'published')->count(),
            'total_views' => BlogPost::where('status', 'published')->sum('views'),
            'total_likes' => BlogLike::count(),
            'total_comments' => BlogComment::count(),
            'categories' => BlogPost::where('status', 'published')
                ->selectRaw('category, count(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category')
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
