<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportantSection;
use App\Models\ImportantLink;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch important sections data sorted by order_number
        // $sections = ImportantSection::filter(request(['search']))
        //     ->orderBy('order_number', 'asc')->with('important_links')->get();
        // Fetch important sections data sorted by name
        $sections = ImportantSection::filter(request(['search']))
            ->orderBy('name', 'asc')->with('important_links')->get();
        // Fetch important sections data sorted by date
        // $sections = ImportantSection::with('important_links')->orderBy('created_at', 'desc')->get();

        // Return admin important sections index view with data
        return view("AdminSection.AdminPageSection", [
            'sections' => $sections
        ]);
    }

    public function changeOrder()
    {
        $sections = ImportantSection::orderBy('order_number')->get();

        return view("AdminSection.AdminPageChangeOrderSection", [
            'sections' => $sections
        ]);
    }

    public function updateOrder(Request $request)
    {
        // Get submitted orders as array: [section_id => order_number]
        $orders = $request->input('order', []);

        $sectionCount = ImportantSection::count();
        $orderValues = array_values($orders);

        foreach ($orders as $sectionId => $order) {
            if (empty($order)) {
                return back()->with('error', 'Semua urutan harus diisi!');
            }
        }

        if (count($orderValues) !== count(array_unique($orderValues))) {
            return back()->with('error', 'Urutan tidak boleh duplikat!');
        }

        foreach ($orderValues as $value) {
            if ($value > $sectionCount) {
                return back()->with('error', 'Urutan melebihi jumlah data section!');
            }
        }

        foreach ($orders as $sectionId => $order) {
            ImportantSection::where('id', $sectionId)->update([
                'order_number' => $order
            ]);
        }

        $request->session()->flash('success', 'Urutan berhasil diperbarui!');
        return redirect()->route('sections.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return admin create important sections form view
        return view("AdminSection.AdminPageTambahSection");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if input is valid
        $request->validate([
            'name' => 'required'
        ]);

        // Check if name input is unique
        // If not, return back with error
        if (count(ImportantSection::where('name', $request->name)->get()) != 0){
            return back()->withError('Section sudah ada');
        }

        // Store inputted imported section in the database
        $section = ImportantSection::create([
            'name' => $request->name,
            'order_number' => ImportantSection::count()+1
        ]);

        // Check if inputted important section exists in the database
        // If yes, redirect to admin important sections index page with success
        // If not, return back with error
        $data = ImportantSection::where('id','=',$section->id)->get();
        if ($data) {
            $request->session()->flash('success', 'Section berhasil ditambahkan!');
            return redirect()->route('sections.index');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch targeted important section
        $section = ImportantSection::findOrFail($id);

        // Return admin edit important section form page with data
        return view("AdminSection.AdminPageEditSection", [
            'section' => $section
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if update input is valid
        $request->validate([
            'name' => 'required'
        ]);

        // Fetch targeted important section
        $section = ImportantSection::findOrFail($id);

        // Check if updated name input is unique
        // If not, return back with error
        if ($section->name != $request->name && count(ImportantSection::where('name', $request->name)->get()) != 0){
            return back()->withError('Section sudah ada');
        }

        // Update targeted important section in database
        $section->name = $request->name;
        $section->save();

        // Check if inputted important section exists in the database
        // If yes, redirect to admin important sections index page with success
        // If not, return back with error
        $data = ImportantSection::where('id','=',$section->id)->get();
        if ($data) {
            $request->session()->flash('success', 'Section berhasil diupdate!');
            return redirect()->route('sections.index');
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
        // Fetch targeted important sections
        $section = ImportantSection::findOrFail($id);

        // Delete targeted important section's important links child
        $links = ImportantLink::where('important_section_id', $id)->delete();
        
        // Delete targeted important section from database
        $section->delete();

        // Redirect to admin important sections index page with success
        request()->session()->flash('success', 'Section berhasil dihapus!');
        return redirect()->route('sections.index');
    }
}
