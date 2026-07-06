<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportantLink;
use App\Models\ImportantSection;

class ApiImportantSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            //sorting alphabetically
            'data' => ImportantSection::orderBy('name', 'asc')->with('important_links')->get()
            //sorting by date
            // 'data' => ImportantSection::with('important_links')->orderBy('created_at', 'desc')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([ //validasi input
            'name' =>'required'
        ]);
        if (count(ImportantSection::where('name', $request->name)->get()) == 0){
            $importantSection = ImportantSection::create($validatedData); //bikin entry data di database
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
    public function show(ImportantSection $importantSection)
    {
        if (count(ImportantSection::where('id', $importantSection->id)->get()) == 0){
            return response()->json(['error' => 'Important section tidak ada'], 400);
        } else {
            $importantLinks = ImportantLink::all()->where('important_section_id', $importantSection->id);
            $response = [
                'importantSection' => $importantSection,
                'importantLinks' => $importantLinks
            ];
            return response()->json([
                'data' => $response
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImportantSection $importantSection)
    {
        $validatedData = $request->validate([ //validasi input
            'name' =>'required'
        ]);
        if (count(ImportantSection::where('id', $importantSection->id)->get()) == 0){
            return response()->json(['error' => 'Important section tidak ada'], 400);
        } else {
            ImportantSection::where('id', $importantSection->id)->update($validatedData);
            $message = "Important section updated";
        }
        return response()->json([
            'message' => $message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImportantSection $importantSection)
    {
        ImportantLink::where('important_section_id', $importantSection->id)->delete();
        ImportantSection::where('id', $importantSection->id)->delete();
        return response()->json([
            'message' => 'item deleted'
        ], 204);
    }
}
