<?php

namespace App\Http\Controllers;

use App\Models\ImportantLink;
use Illuminate\Http\Request;

class ApiImportantLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => ImportantLink::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([ //validasi input
            'name' =>'required',
            'link' =>'required | url | active_url',
            'important_section_id' =>'required'
        ]);
        if (count(ImportantLink::where('name', $request->name)->get()) == 0){
            $importantSection = ImportantLink::create($validatedData); //bikin entry data di database
            return response()->json([
                'data' => $importantSection
            ]);
        } else {
            return response()->json(['error' => 'Tag sudah ada'], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ImportantLink $importantLink)
    {
        if (count(ImportantLink::where('id', $importantLink->id)->get()) == 0){
            return response()->json(['error' => 'Important link tidak ada'], 400);
        } else {
            return response()->json([
                'data' => $importantLink
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImportantLink $importantLink)
    {
        $validatedData = $request->validate([ //validasi input
            'name' => 'required',
            'link' => 'required | url | active_url'
        ]);
        if (count(ImportantLink::where('id', $importantLink->id)->get()) == 0){
            return response()->json(['error' => 'Important link tidak ada'], 400);
        } else {
            ImportantLink::where('id', $importantLink->id)->update($validatedData);
            $message = "Important link updated";
        }
        return response()->json([
            'message' => $message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImportantLink $importantLink)
    {
        ImportantLink::where('id', $importantLink->id)->delete();
        return response()->json([
            'message' => 'item deleted'
        ], 204);
    }
}
