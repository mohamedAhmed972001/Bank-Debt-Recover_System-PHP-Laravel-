<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;

class InvoicesReportController extends Controller
{
    public function index()
    {
        return view('reports.invices_report');
    }
    
    public function Search_invoices(Request $request)
    {
        $rdio = $request->rdio;

        if($rdio==1) { // ف حالة البحث بنوع الفاتورة 
            // ف حالة عدم تحديد التواريخ
            if($request->type && $request->start_at ==''&& $request->end_at=='') {
                $type = $request->type;
                $invoices = invoices::where('Status',$type)->get();
                return view('reports.invices_report',compact('invoices','type'));
            }else { // ف حالة تحديد التواريخ
                $type = $request->type;
                $start_at = $request->start_at;
                $end_at = $request->end_at;
                $invoices = invoices::whereBetween('invoice_Date',[$start_at,$end_at])->where('Status',$type)->get();
                return view('reports.invices_report',compact('invoices','type','start_at','end_at'));
            }

        }else { // ف حالة البحث برقم الفاتورة
            $invoice_number = $request->invoice_number;
            $invoices = invoices::where('invoice_number',$invoice_number)->get();
            return view('reports.invices_report',compact('invoices'));   
        }
    }
    

}
