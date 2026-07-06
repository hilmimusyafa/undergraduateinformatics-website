<?php

namespace App\Http\Controllers;

use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Http\Request;

class ApiTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            //sorting alphabetically
            'data' => Tag::orderBy('name', 'asc')->get()
            //sorting by date
            // 'data' => Tag::orderBy('created_at', 'desc')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([ //validasi input
            'name' =>'required',
            'description' => 'nullable'
        ]);
        if (count(Tag::where('name', $request->name)->get()) == 0){
            $tag = Tag::create($validatedData); //bikin entry data di database
            return response()->json([
                'data' => $tag
            ]);
        } else {
            return response()->json(['error' => 'Tag sudah ada'], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        if (count(Tag::where('id', $tag->id)->get()) == 1){
            return response()->json([
                'data' => $tag
            ]);
            
        } else {
            return response()->json(['error' => 'Tag tidak ada'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $validatedData = $request->validate([ //validasi input
            'name' =>'required',
            'description' => 'nullable'
        ]);
        $updateTag = Tag::where('id', $tag->id)->first();
        if ($updateTag) {
            if ($updateTag->name != $validatedData->name && count(Tag::where('name', $validatedData->name)->get()) != 0){
                return response()->json(['error' => 'Tag sudah ada'], 400);
            }

            if ($updateTag->name == "S1 Informatika" && $validatedData->name != "S1 Informatika") {
                return response()->json(['error' => 'Nama tag default tidak dapat diubah'], 400);
            }

            $updateTag->update($validatedData);
            return response()->json([
                'message' => "Tag updated"
            ]);
        } else {
            return response()->json(['error' => 'Tag tidak ada'], 400);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        if ($tag->name == "S1 Informatika") {
            return response()->json([
                'error' => 'Tag default tidak dapat dihapus'
            ], 400);
        }
        Tag::where('id', $tag->id)->delete();
        PostTag::where('tag_id', $tag->id)->delete();
        return response()->json([
            'message' => 'item deleted'
        ], 204);
    }
}
