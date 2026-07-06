<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportantLink;
use App\Models\ImportantSection;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch important links data sorted by name
        $links = ImportantLink::filter(request(['search']))->get()
            ->sortBy([['important_section.name'],['name', 'desc']]);

        // Return admin important link index page with data
        return view("AdminLinkPenting.AdminPageLink", [
            'links' => $links
        ]);
    }

    /**
     * Display a listing of the resource for non-admin.
     */
    public function index_home()
    {
        // Fetch important sections data sorted by name
        $sections = ImportantSection::all()->sortBy('order_number');

        // Return important link index page with data
        return view("LinkPentingPage", [
            'sections' => $sections
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch important sections data sorted by name for input form
        $sections = ImportantSection::all()->sortBy('name');

        if ($sections->isEmpty()) {
            return redirect()->route('sections.create')
                ->withError('Silahkan buat section link terlebih dahulu');
        }

        // Return admin create important link form page with data
        return view("AdminLinkPenting.AdminPageTambahLink", [
            'sections' => $sections
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if inputs are valid
        $request->validate([
            'section_id' => 'required',
            'name' => 'required',
            'link' => 'required | url | active_url'
        ]);


        // Store important link in database
        $link = ImportantLink::create([
            'important_section_id' => $request->section_id,
            'name' => $request->name,
            'link' => $request->link
        ]);

        // Check if inputted important link exists in the database
        // If yes, redirect to admin important links index page with success
        // If not, return back with error
        $data = ImportantLink::where('id','=',$link->id)->get();
        if ($data) {
            $request->session()->flash('success', 'Link berhasil ditambahkan!');
            return redirect()->route('links.index');
        } else {
            return back()->withError('Terdapat kesalahan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch important sections for form & target link
        $sections = ImportantSection::all();
        $link = ImportantLink::findOrFail($id);

        // Return admin edit important link form page
        return view("AdminLinkPenting.AdminPageEditLink", [
            'sections' => $sections,
            'link' => $link
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if update input is valid
        $request->validate([
            'section_id' => 'required',
            'name' => 'required',
            'link' => 'required | url | active_url'
        ]);

        // Fetch important link update target
        $link = ImportantLink::findOrFail($id);

        // Update targeted important link
        $link->important_section_id = $request->section_id;
        $link->name = $request->name;
        $link->link = $request->link;
        $link->save();

        // Check if updated important link exists in the database
        // If yes, redirect to admin important links index page with success
        // If not, return back with error
        $data = ImportantLink::where('id','=',$link->id)->get();
        if ($data) {
            $request->session()->flash('success', 'Link berhasil diupdate!');
            return redirect()->route('links.index');
        } else {
            return back()->withError('Terdapat kesalahan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Fetch targeted important link
        $link = ImportantLink::findOrFail($id);

        // Delete targeted important link from database
        $link->delete();

        // Redirect to admin important links index page with success
        request()->session()->flash('success', 'Link berhasil dihapus!');
        return redirect()->route('links.index');
    }
}
