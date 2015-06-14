<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = Post::where('created_at', '<=', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->paginate(config('blog.posts_per_page'));

        return view('blog.index', compact('posts'));
    }

    public function showPost($id)
    {
        $post = Post::findOrFail($id);
        return view('blog.post')->withPost($post);
    }

    /**
     * Show the post edit form
     *
     * @param  Post $post
     * @return Response
     */
    public function editPost(Post $post)
    {
        return view('blog.edit')->withPost($post);
    }

    /**
     * Show the new post form
     *
     * @return Response
     */
    public function newPost()
    {
        return view('blog.new');
    }


    /**
     * Show the new post form
     *
     * @return Response
     */
    public function createPost()
    {
        $post = new Post();
        $post->setAttribute('title', Input::get('title'));
        $post->setAttribute('content', Input::get('content'));
        $post->save();

        return Redirect::to('blog')->with('message', 'Post ID: ' . $post->getAttribute('id') . ' is created.');
    }

    /**
     * Delete the post
     *
     * @param  Post $post
     * @return Response
     */
    public function deletePost(Post $post)
    {
        $post->delete();
        return Redirect::to('blog')->with('message', 'Post ID: ' . $post->getAttribute('id') . ' is deleted.');
    }

    /**
     * Update the post
     *
     * @param  Post $post
     * @return Response
     */
    public function updatePost(Post $post)
    {
        $post->setAttribute('title', Input::get('title'));
        $post->setAttribute('content', Input::get('content'));
        $post->save();
        return Redirect::to('blog')->with('message', 'Post ID: ' . $post->getAttribute('id') . ' is updated.');
    }
}