<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;

class CustomersReportController extends Controller
{
    public function index()
    {
        $sections = sections::all();
        return view('reports.customers_report', compact('sections'));
    }

    public function Search_customers(Request $request)
    {
        // في حالة البحث بدون تاريخ
        if ($request->Section && $request->product && $request->start_at == '' && $request->end_at == '') {
            $invoices = invoices::where('section_id', $request->Section)->where('product',$request->product)->get();
            $sections = sections::all();
        }
        // في حالة البحث بتاريخ
        else {
            $start_at = $request->start_at;
            $end_at = $request->end_at;
            $invoices = invoices::whereBetween('invoice_Date', [$start_at, $end_at])->where('section_id',$request->Section)->where('product',$request->product)->get();
            $sections = sections::all();
        }
        return view('reports.customers_report', compact('sections','invoices'));
    }
}
