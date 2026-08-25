<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Support\Facades\File;

class ApiPostController extends Controller
{
    /**
    * Show listings of posts resources
    */
    public function index () { 
        $data = Post::with('post_tags.tag')
                ->orderBy('created_at', 'desc')
                ->get();


        return response()->json([
            'data' => $data
        ],200);
    }

    /**
    * Store post data to database
    */
    public function store(Request $request)
    {
        $tags = $request->tag;
      
        $validateData = $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'body'   => 'required',
            
        ]);
        $tagDefault = Tag::where('name', "S1 Informatika")->first();
        
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $foto_ekstensi = $gambar->getClientOriginalName();
            $foto_nama = time() . "_" . $foto_ekstensi;

            $pathPublic = app()->make('path.public');
            $pathPublic = $pathPublic . "/images/posts";
            $gambar->move($pathPublic, $foto_nama);

            $gambar_path = 'images/posts/' . $foto_nama;
            $validateData['image'] = $gambar_path;
            $post = Post::create($validateData);
          
            PostTag::create([
                'post_id' => $post->id,
                'tag_id' => $tagDefault->id
            ]);
            foreach ($tags as $tag) {
                $tag_id = intval($tag);
                if (count(PostTag::where('post_id', $post->id)->where('tag_id', $tag_id)->get()) != 0) {
                    continue;
                }
                PostTag::create([
                    'post_id' => "$post->id",
                    'tag_id' => "$tag_id"
                ]);
                
            };
            
            return response()->json([
                'succes' => "Berhasil Menambahkan Post"
            ],202);
        };
        $validateData['image'] = "images/placeholder.png";
        $post = Post::create($validateData);  
        
        PostTag::create([
            'post_id' => $post->id,
            'tag_id' => $tagDefault->id
        ]);
        foreach ($tags as $tag) {
            $tag_id = intval($tag);
            if (count(PostTag::where('post_id', $post->id)->where('tag_id', $tag_id)->get()) != 0) {
                continue;
            }
            PostTag::create([
                'post_id' => "$post->id",
                'tag_id' => "$tag_id"
            ]);    
        };

        return response()->json([
            'succes' => "Berhasil Menambahkan Post"
        ],202);
          
    }

    /**
    * Update post data
    */
    public function update(Request $request,$id){
      
        $tags = $request->tag;
    
        $validateData = $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'body'   => 'required',
            
        ]);


    
        $tagDefault = Tag::where('name', "S1 Informatika")->first();
       
        if($request->cekgambar == "1") {       
            $post = Post::where('id',$id)->first();
           
            $pathPublic = app()->make('path.public');
            File::delete($pathPublic . "/".$post->image);
       
            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $foto_ekstensi = $gambar->getClientOriginalName();
                $foto_nama = time() . "_" . $foto_ekstensi;
    
                $pathPublic = app()->make('path.public');
                $pathPublic = $pathPublic . "/images/posts";
                $gambar->move($pathPublic, $foto_nama);
    
                $gambar_path = 'images/posts/' . $foto_nama;
                $validateData['image'] = $gambar_path;

                Post::where('id',$id) 
                        ->update($validateData);
                $postTags = PostTag::where('post_id', $post->id)->delete();
               
                PostTag::create([
                    'post_id' => $post->id,
                    'tag_id' => $tagDefault->id
                ]);

                foreach ($tags as $tag) {
                    $tag_id = intval($tag);
                    if (count(PostTag::where('post_id', $post->id)->where('tag_id', $tag_id)->get()) != 0) {
                        continue;
                    }
                    PostTag::create([
                        'post_id' => $post->id,
                        'tag_id' => $tag_id
                    ]);
                }
                return response()->json([
                    'succes' => "Berhasil Mengedit Post"
                ],202);
            };
          
            $post->image = "images/placeholder.png";
            $post->title = $request->title;
            $post->subtitle = $request->subtitle;
            $post->body = $request->body;
            $post->save();  
            $postTags = PostTag::where('post_id', $post->id)->delete();
         
            PostTag::create([
                'post_id' => $post->id,
                'tag_id' => $tagDefault->id
            ]);
            
            foreach ($tags as $tag) {
                $tag_id = intval($tag);
                if (count(PostTag::where('post_id', $post->id)->where('tag_id', $tag_id)->get()) != 0) {
                    continue;
                }
                PostTag::create([
                    'post_id' => $post->id,
                    'tag_id' => $tag_id
                ]);
            }

            return response()->json([
                'succes' => "Berhasil Mengedit Post"
            ],202);

        }else{
      
            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $foto_ekstensi = $gambar->getClientOriginalName();
                $foto_nama = time() . "_" . $foto_ekstensi;
    
                $pathPublic = app()->make('path.public');
                $pathPublic = $pathPublic . "/images/posts";
                $gambar->move($pathPublic, $foto_nama);
    
                $gambar_path = 'images/posts/' . $foto_nama;
                $validateData['image'] = $gambar_path;

                Post::where('id',$id) 
                        ->update($validateData);
                
                PostTag::where('post_id', $id)->delete();

                PostTag::create([
                    'post_id' => $id,
                    'tag_id' => $tagDefault->id
                ]);
                foreach ($tags as $tag) {
                    $tag_id = intval($tag);
                    PostTag::create([
                        'post_id' => $id,
                        'tag_id' => $tag_id
                    ]);
                }
                return response()->json([
                    'succes' => "Berhasil Mengedit Post"
                ],202);
            }else{
              
                Post::where('id',$id) 
                        ->update($validateData);
                 PostTag::where('post_id', $id)->delete();
                PostTag::create([
                    'post_id' => $id,
                    'tag_id' => $tagDefault->id
                ]);
              
               
               
                foreach ($tags as $tag) {
                    $tag_id = intval($tag);
                    PostTag::create([
                        'post_id' => $id,
                        'tag_id' => $tag_id
                    ]);
                }
          
                return response()->json([
                    'succes' => "Berhasil Mengedit Post"
                ],202);
            } 
        }
    }

    /**
    * Delete post data
    */
    public function destroy(Request $request) {
        $post = Post::find($request->id);
        if($post->image != 'images/placeholder.png') {
            $pathPublic = app()->make('path.public');
            File::delete($pathPublic . "/".$post->image);
        };
        
        $post->delete();
        $postTags = PostTag::where('post_id', $request->id)->delete();
        
        return response()->json([
            'succes' => "Delete Berhasil"
        ],202);
    }
}