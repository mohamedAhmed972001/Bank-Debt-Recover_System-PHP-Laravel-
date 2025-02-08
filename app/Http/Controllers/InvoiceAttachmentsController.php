<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'file_name' => 'mimes:jpeg,jpg,png,pdf|max:2048' // يسمح برفع صور بصيغة JPEG, JPG, PNG بحجم أقصى 2 ميغابايت
        ], [
            'file_name.mimes' => 'صيغة المرفق يجب ان تكون jpeg,jpg,png,pdf',
        ]);
        $image = $request->file('file_name');
        $file_name = $image->getClientOriginalName();
        $invoice_number = $request->invoice_number;
        $invoice_id = $request->invoice_id;

        $attachments = new invoice_attachments();
        $attachments->file_name = $file_name;
        $attachments->invoice_number = $invoice_number;
        $attachments->id_Invoice = $invoice_id;
        $attachments->Created_by = Auth::user()->name;
        $attachments->save();
        // move file_name 
        $request->file_name->move(public_path('Attachments/'.$invoice_number), $file_name);

        return redirect()->back()->with(['Add' => 'تم اضافه المرفق بنجاح ']);
    }

    /**
     * Display the specified resource.
     */
    public function show(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoice_attachments $invoice_attachments)
    {
        //
    }
}
