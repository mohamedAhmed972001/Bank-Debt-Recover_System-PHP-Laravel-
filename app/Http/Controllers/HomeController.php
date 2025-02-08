<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $chart_options = [
            'chart_title' => 'طبقا لحالة الدفع',
            'chart_type' => 'bar', // line or pie
            'report_type' => 'group_by_string', // يعتمد على تجميع القيم النصية
            'model' => 'App\Models\Invoices', // اسم النموذج المرتبط بالجدول
            'group_by_field' => 'Status', // الحقل الذي ستتم تجميع القيم بناءً عليه
            'aggregate_function' => 'count', // نريد عدّ الفواتير لكل حالة
        ];
        $chart1 = new LaravelChart($chart_options);

        return view('dashboard',compact('chart1'));
    }
}
