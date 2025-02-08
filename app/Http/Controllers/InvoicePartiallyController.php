<?php

namespace App\Http\Controllers;

use App\Models\invoice_partially;
use App\Models\invoices;
use Illuminate\Http\Request;

class InvoicePartiallyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_invoices = invoices::where('status', 'مدفوعة جزئيا')->get();
        return view('invoices/invoices_partially',compact('all_invoices'));
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
    public function show(invoice_partially $invoice_partially)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoice_partially $invoice_partially)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoice_partially $invoice_partially)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoice_partially $invoice_partially)
    {
        //
    }
}
