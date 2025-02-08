<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_sections = sections::all();
        return view('sections/sections', compact('all_sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_name' => 'required|unique:sections,section_name|max:255',
            'description' => 'required',
        ], [
            'section_name.required' => 'يرجى ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقا',
            'description.required' => 'يرجى ادخال الملاحظات',
        ]);
        sections::create([
            'section_name' => $validated['section_name'],
            'description' => $validated['description'],
            'Created_by' => Auth::user()->name,
        ]);
        return redirect()->back()->with(['Add' => 'تم اضافه القسم بنجاح ']);
    }

    /**
     * Display the specified resource.
     */
    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $validated = $request->validate([
            'section_name' => 'required|max:255|unique:sections,section_name,' . $input['id'],
            'description' => 'required',
        ], [
            'section_name.required' => 'يرجى ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقا',
            'description.required' => 'يرجى ادخال الملاحظات',
        ]);
        $sections = sections::find($input['id']);
        $sections->update([
            'section_name' => $validated['section_name'],
            'description' => $validated['description'],
        ]);
        return redirect()->back()->with(['edit' => 'تم تعديل القسم بنجاح ']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        sections::find($id)->delete();
        return redirect()->back()->with(['delete' => 'تم حذف القسم بنجاح']);
    }
}
