<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = sections::all();
        $products = products::all();
        return view('products/products', compact('sections', 'products'));
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
            'product_name' => 'required|unique:products,product_name|max:255',
            'section_id' => 'required',
            'description' => 'required',
        ], [
            'product_name.required' => 'يرجى ادخال اسم المنتج',
            'product_name.unique' => 'اسم المنتج مسجل مسبقا',
            'section_id.required' => 'يرجى تحديد القسم',
            'description.required' => 'يرجى ادخال الملاحظات',
        ]);

        // استخدام $validated بدلاً من $input
        products::create([
            'product_name' => $validated['product_name'],
            'section_id' => $validated['section_id'],
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with(['Add' => 'تم اضافه المنتج بنجاح ']);
    }

    /**
     * Display the specified resource.
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(products $products)
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
            'product_name' => 'required|max:255|unique:products,product_name,' . $input['id'],
            'section_id' => 'required',
            'description' => 'required',
        ], [
            'product_name.required' => 'يرجى ادخال اسم المنتج',
            'product_name.unique' => 'اسم المنتج مسجل مسبقا',
            'section_id.required' => 'يرجى تحديد القسم',
            'description.required' => 'يرجى ادخال الملاحظات',
        ]);
        $products = products::find($input['id']);
        $products->update([
            'product_name' => $validated['product_name'],
            'section_id' => $validated['section_id'],
            'description' => $validated['description'],
        ]);
        return redirect()->back()->with(['edit' => 'تم تعديل المنتج بنجاح ']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        products::find($id)->delete();
        return redirect()->back()->with(['delete' => 'تم حذف المنتج بنجاح']);
    }
}
