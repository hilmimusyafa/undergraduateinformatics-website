<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use App\Models\PostTag;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch filtered posts data sorted by date updated
        $posts = Post::filter(request(['search']))->orderBy('updated_at', 'desc')->get();

        // Return admin posts index page with data
        return view("AdminInformasi.AdminPageInformasi", [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        /*
        // Fetch tags data for input form
        $tags = Tag::all();

        // Return admin create post form with data
        return view("AdminInformasi.AdminPageTambahInformasi", [
            'tags' => $tags
        ]);
        */
        if ($request->input('viewGenerated') == true) {
            if ($request->input('selected')) {
                $tags = Tag::where('name', '!=', 'S1 Informatika')
                ->where('name', 'like', '%' . $request->input('query') . '%')
                ->whereNotIn('id', $request->input('selected'))->get();
                
                $selectedTags = Tag::whereIn('id', $request->input('selected'))->get();
                return view('AdminInformasi.TagLiveSearchResult', compact('tags', 'selectedTags'));
            }

            $tags = Tag::where('name', '!=', 'S1 Informatika')
                ->where('name', 'like', '%' . $request->input('query') . '%')->get();
            return view('AdminInformasi.TagLiveSearchResult', compact('tags'));
        }

        // Fetch tags data for input form
        $tags = Tag::where('name', '!=', 'S1 Informatika')->get();

        // Return admin create post form with data
        return view("AdminInformasi.AdminPageTambahInformasi", [
            'tags' => $tags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if post inputs are valid
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'body' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:2048',
            'tags' => 'required|min:1'
        ]);

        $request->tags = array_unique($request->tags);

        $tagDefault = Tag::where('name', "S1 Informatika")->first();

        // Store inputted post data in the database
        $post = Post::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'body' => $request->body,
        ]);
        
        PostTag::create([
            'post_id' => $post->id,
            'tag_id' => $tagDefault->id
        ]);

        // Iterate inputted post's tags
        foreach($request->tags as $tag) {
            if (count(PostTag::where('post_id', $post->id)->where('tag_id', $tag)->get()) == 0) {
            // Store PostTag data in the database
                PostTag::create([
                    'post_id' => $post->id,
                    'tag_id' => $tag
                ]);
            }
        }

        // Check if input has image
        // If yes, store image in storage and save the image link in database
        // If not, save the image link as dummy image
        if ($request->image) {
            $imageName = time().'_'.$request->image->getClientOriginalName();
            $pathPublic = app()->make('path.public');
            $pathPublic = $pathPublic . "/images/posts";
            $request->image->move($pathPublic, $imageName);
            $post->image = "images/posts/".$imageName;
        } else {
            $post->image = "images/DummyImage.png";
        }

        // Update record in database
        $post->save();

        // Check if inputted post exists in the database
        // If yes, redirect to admin posts index page with success
        // If not, return back with error
        $data = Post::where('id','=',$post->id)->get();
        if ($data) {
            $request->session()->flash('success', 'Post berhasil ditambahkan!');
            return redirect()->route('posts.index');
        } else {
            return back()->withErrors([
                'message' => 'Terdapat kesalahan'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch targeted post data
        $post = Post::findOrFail($id);

        // Return post detail view with data
        return view("PostPage", [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch targeted post data & tags for input form
        $post = Post::findOrFail($id);
        $tags = Tag::where('name', '!=', 'S1 Informatika')->get();

        // Return admin edit post form view with data
        return view("AdminInformasi.AdminPageEditInformasi", [
            'post' => $post,
            'tags' => $tags
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if update inputs are valid
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'body' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'tags' => 'required|min:1'
        ]);

        $request->tags = array_unique($request->tags);

        $tagDefault = Tag::where('name', "S1 Informatika")->first();

        // Fetch post update target
        $post = Post::findOrFail($id);

        // Update targeted post
        $post->title = $request->title;
        $post->subtitle = $request->subtitle;
        $post->body = $request->body;

        // Check if update input has image
        // If yes, delete previous image, store image, & update image link
        if ($request->image) {
            if($post->hasImage()) {
                $pathPublic = app()->make('path.public');
                File::delete($pathPublic . "/".$post->image);
            }
            $imageName = time().'_'.$request->image->getClientOriginalName();  
            $pathPublic = app()->make('path.public');
            $pathPublic = $pathPublic . "/images/posts";
            $request->image->move($pathPublic, $imageName);
            $post->image = "images/posts/".$imageName;
        } else if($request->has('deleteGambar')) {
            $pathPublic = app()->make('path.public');
            File::delete($pathPublic . "/".$post->image);
            
            $post->image = "images/DummyImage.png";
        }

        // Delete previous PostTag data 
        PostTag::where('post_id', $id)->delete();

        PostTag::create([
            'post_id' => $post->id,
            'tag_id' => $tagDefault->id
        ]);

        // Iterate updated post's tags
        foreach($request->tags as $tag) {
            if (count(PostTag::where('post_id', $post->id)->where('tag_id', $tag)->get()) == 0) {
                // Store updated PostTag data in the database
                PostTag::create([
                    'post_id' => $post->id,
                    'tag_id' => $tag
                ]);
            }
        }

        // Update record in database
        $post->updated_at = now();
        $post->save();

        // Check if inputted post exists in the database
        // If yes, redirect to admin posts index page with success
        // If not, return back with error
        $data = Post::where('id','=',$post->id)->get();
        if ($data) {
            $request->session()->flash('success', 'Post berhasil diupdate!');
            return redirect()->route('posts.index');
        } else {
            return back()->withErrors([
                'message' => 'Terdapat kesalahan'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Fetch targeted post data
        $post = Post::findOrFail($id);

        // Delete targeted post's image if exist
        if($post->hasImage()) {
            $pathPublic = app()->make('path.public');
            File::delete($pathPublic . "/".$post->image);
        }
        
        // Delete PostTags data with targeted post id
        PostTag::where('post_id', $id)->delete();

        // Delete targeted post from database
        $post->delete();

        // Redirect to admin post index page with success
        request()->session()->flash('success', 'Post berhasil dihapus!');
        return redirect()->route('posts.index');
    }

    /**
     * Find post with search
     */
    public function search(Request $request)
    {
        // Fetch tags data for filter dropdown
        $tags = Tag::all();

        // Fetch posts data based on tag & search filter
        if ($request->query('tags')) {
            $tags_search = Tag::whereIn('id', $request->query('tags'))->get();
            $posts_search = Post::filter(request(['search']))->whereHas('tags', function($query) {
                $query->whereIn('tags.id', request()->query('tags'));
            })->get();
        } else {
            $tags_search = Tag::all();
            $posts_search = Post::filter(request(['search']))->whereHas('tags', function($query) {
                $query->whereNotNull('tags.id');
            })->get();
        }
        
        // Return search page view with data
        return view('SearchPage', [
            'tags' => $tags,
            'tags_search' => $tags_search,
            'posts_search' => $posts_search
        ]);
    }

    // public function tagLiveSearch(Request $request)
    // {
    //     $query = $request->input('query');
    //     if ($query) {
    //         $tags = Tag::where('name', 'like', '%' . $query . '%')->get();

    //         return view('AdminInformasi.TagLiveSearchResult', compact('tags'));
    //     }
    // }
}
