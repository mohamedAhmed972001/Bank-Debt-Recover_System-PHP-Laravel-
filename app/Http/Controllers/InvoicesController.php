<?php

namespace App\Http\Controllers;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\invoice_attachments;
use App\Models\invoices;
use App\Models\invoices_details;
use App\Models\sections;
use App\Models\User;
use App\Notifications\Add_invoice_new;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_invoices = invoices::all();
        return view('invoices/invoices',compact('all_invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = sections::all();
        return view('invoices/add_invoice',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pic' => 'mimes:jpeg,jpg,png,pdf|max:2048' // يسمح برفع صور بصيغة JPEG, JPG, PNG بحجم أقصى 2 ميغابايت
        ], [
            'pic.mimes' => 'صيغة المرفق يجب ان تكون jpeg,jpg,png,pdf',
        ]);
        
        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = invoices::latest()->first()->id; // هنجيب اى دي الفالتورة الحالية من الجدول ال فوق
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if($request->hasFile('pic')) 
        {
            $invoice_id = invoices::latest()->first()->id; // هنجيب اى دي الفاتورة الحالية من الجدول ال فوق
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;
 
            $attachments = new invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->id_Invoice = $invoice_id;
            $attachments->Created_by = Auth::user()->name;
            $attachments->save();
            
            // move pic 
            $request->pic->move(public_path('Attachments/'.$invoice_number), $file_name);
        }

        //$user = User::first();
        //$user->notify(new AddInvoice($invoice_id)); // لارسال اشعار ع الجيميل عند اضافة فاتورة
        
        $users = User::all();
        foreach($users as $user){
            $user->notify(new Add_invoice_new($invoice_id)); 
        }
        
        return redirect()->back()->with(['Add' => 'تم اضافه الفاتورة بنجاح وإرسال اشعار للبريد الالكتروني ولكل المستخدمين']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = invoices::where('id',$id)->first(); 
        return view('invoices.status_update', compact('invoices'));
    }

    public function invoice_print($id)
    {
        $invoice = invoices::where('id',$id)->first(); 
        return view('invoices.invoice_print', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = invoices::where('id',$id)->first();
        $sections = sections::all();
        return view('invoices.edit_invoice',compact('invoices','sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoices = invoices::findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);
        return redirect()->back()->with(['edit' => 'تم تعديل الفاتورة بنجاح ']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoices::withTrashed()->where('id',$id)->first();
        $details = invoice_attachments::where('id_Invoice',$id)->first(); // هيجيب اول مرفق من مرفقات الفاتورة دى
        $id_page = $request->id_page;
        if(!$id_page==2) {
            if(!empty($details->invoice_number)){
                Storage::disk('public_uploads')->deleteDirectory($details->invoice_number); // هيحذف الفولدر اللي فيه مرفقات الفاتورة دى كلها    
            }                             // ولازم الترتيب ده .. احذف مرفقات الفاتورة وبعدين الفاتورة  
            $invoices->forceDelete();    // هتتحذف من عند اليوزر وكمان من الداتابيز forceDelete()
                                    // هتتحذف من عند اليوزر لكن هتفضل موجودة فالداتابيز Delete()
            return redirect('/invoices-archive')->with(['delete_invoice' => 'تم حذف الفاتورة بنجاح ']);    
        }else {
            $invoices->delete();
            return redirect('/invoices-archive')->with(['archive_invoice' => 'تم ارشفة الفاتورة بنجاح ']);    
        }
    }

    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }

    public function Status_Update($id, Request $request)
    {
        $invoices = invoices::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }elseif ($request->Status === 'غير مدفوعة') {

            $invoices->update([
                'Value_Status' => 2,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 2,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');
    }

    public function invoices_paid(){
        $invoices_paid = invoices::where('Value_Status', '1')->get();
        return view('invoices/invoices_paid',compact('invoices_paid'));
    }
    public function invoices_unpaid(){
        $invoices_unpaid = invoices::where('Value_Status', '2')->get();
        return view('invoices/invoices_unpaid',compact('invoices_unpaid'));
    }
    public function invoices_partially(){
        $invoices_partially = invoices::where('Value_Status', '3')->get();
        return view('invoices/invoices_partially',compact('invoices_partially'));
    }
 
    public function export() 
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    public function MarkAsRead_All()
    {
        $unreadnotifications = auth()->user()->unreadNotifications;

        if($unreadnotifications){
            $unreadnotifications->markAsRead();
            return back();
        }
    }
}
