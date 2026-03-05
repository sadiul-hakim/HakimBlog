<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class PostController extends Controller
{
    public function addPost()
    {
        $pCategories = ParentCategory::whereHas('children')->orderBy('name', 'asc')->get();
        $categories = Category::where('parent', 0)->orderBy('name', 'asc')->get();
        $data = [
            'pageTitle' => 'Add New Post',
            'pCategories' => $pCategories,
            'categories' => $categories
        ];
        return view('back.pages.add_post', $data);
    }
    public function createPost(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts,title',
            'content' => 'required',
            'category' => 'required|exists:categories,id',
            'featured_image' => 'required|mimes:png,jpg,jpeg,webp,svg|max:1024'
        ]);

        // Create Post
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $folderPath = 'images/posts/';
            $extension = '.' . $file->extension();
            $fileName = 'IMG_' . uniqid() . $extension;
            // $fullPath = $folderPath . $fileName;
            // $uploaded = Storage::disk('public')->put($fullPath, File::get($file));
            $uploaded = $file->move(public_path($folderPath), $fileName);

            if ($uploaded) {
                $resized_path = $folderPath . 'resized/';
                if (!File::isDirectory($resized_path)) {
                    File::makeDirectory($resized_path, 0777, true, true);
                }
                // Thumbnail (Aspect Ratio: 1)
                Image::read($folderPath . $fileName)->cover(300, 300)->save($resized_path . 'thumb_' . $fileName);

                // Resized Image (Aspect Ratio: 1.6)
                Image::read($folderPath . $fileName)->cover(512, 320)->save($resized_path . 'resized_' . $fileName);

                // Make a new Post and save
                $post = new Post();
                $post->author_id = Auth::id();
                $post->category = $request->category;
                $post->title = $request->title;
                $post->content = $request->content;
                $post->featured_image = $fileName;
                $post->tags = $request->tags;
                $post->meta_keywords = $request->meta_keywords;
                $post->meta_description = $request->meta_description;
                $post->visibility = $request->visibility;
                $saved = $post->save();
                if ($saved) {
                    return response()->json(['status' => 1, 'type' => 'success', 'message' => 'Post has been added.']);
                } else {
                    return response()->json(['status' => 0, 'type' => 'error', 'message' => 'Something went wrong.']);
                }
            } else {
                return response()->json(['status' => 0, 'type' => 'error', 'message' => 'Something went wrong while upload featured image.']);
            }
        }
    }
    public function allPosts() {}
}
