<?php

namespace App\Http\Controllers;

use App\Models\invoice_paid;
use App\Models\invoices;
use Illuminate\Http\Request;

class InvoicePaidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_invoices = invoices::where('status', 'مدفوعة')->get();
        return view('invoices/invoices_paid',compact('all_invoices'));
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
    public function show(invoice_paid $invoice_paid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoice_paid $invoice_paid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoice_paid $invoice_paid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoice_paid $invoice_paid)
    {
        //
    }
}
