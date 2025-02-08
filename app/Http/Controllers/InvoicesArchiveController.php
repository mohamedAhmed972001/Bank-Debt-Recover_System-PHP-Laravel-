<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoices_archive;
use Illuminate\Http\Request;

class InvoicesArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices_archive = invoices::onlyTrashed()->get();
        return view('invoices/invoices_archive',compact('invoices_archive'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_archive $invoices_archive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices_archive $invoices_archive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = invoices::withTrashed()->find($invoice_id);
        $invoice->restore();
        return redirect('/invoices')->with(['restore_from_archive' => 'تم استعادة الفاتورة بنجاح ']);    

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoices_archive $invoices_archive)
    {
        //
    }
}
