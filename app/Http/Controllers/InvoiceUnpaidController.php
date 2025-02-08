<?php

namespace App\Http\Controllers;

use App\Models\invoice_unpaid;
use App\Models\invoices;
use Illuminate\Http\Request;

class InvoiceUnpaidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_invoices = invoices::where('status', 'غير مدفوعة')->get();
        return view('invoices/invoices_unpaid',compact('all_invoices'));
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
    public function show(invoice_unpaid $invoice_unpaid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoice_unpaid $invoice_unpaid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoice_unpaid $invoice_unpaid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoice_unpaid $invoice_unpaid)
    {
        //
    }
}
