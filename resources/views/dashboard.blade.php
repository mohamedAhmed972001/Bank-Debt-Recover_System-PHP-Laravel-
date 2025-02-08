@extends('layouts.master')
@section('title')
    لوحة التحكم - برنامج الفواتير
@stop

@section('css')
    <link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">لوحة التحكم - برنامج الفواتير</h2>
        </div>
    </div>
@endsection
@section('content')
    @php
        $totalInvoices = \App\Models\invoices::count();
        $totalSum = \App\Models\invoices::sum('Total');
        $unpaidInvoices = \App\Models\invoices::where('Value_Status', 2)->count();
        $unpaidSum = \App\Models\invoices::where('Value_Status', 2)->sum('Total');
        $paidInvoices = \App\Models\invoices::where('Value_Status', 1)->count();
        $paidSum = \App\Models\invoices::where('Value_Status', 1)->sum('Total');
        $partialInvoices = \App\Models\invoices::where('Value_Status', 3)->count();
        $partialSum = \App\Models\invoices::where('Value_Status', 3)->sum('Total');
    @endphp

    <div class="row row-sm">
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <a href="{{ url('/invoices') }}">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <h6 class="mb-3 tx-12 text-white">اجمالي الفواتير</h6>
                        <h4 class="tx-20 font-weight-bold mb-1 text-white">{{ number_format($totalSum, 2) }}</h4>
                        <p class="mb-0 tx-12 text-white op-10">عدد الفواتير: {{ $totalInvoices }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <a href="{{ url('/invoices-unpaid') }}">
                <div class="card overflow-hidden sales-card bg-danger-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <h6 class="mb-3 tx-12 text-white">الفواتير الغير مدفوعة</h6>
                        <h4 class="tx-20 font-weight-bold mb-1 text-white">{{ number_format($unpaidSum, 2) }}</h4>
                        <p class="mb-0 tx-12 text-white op-10">عدد الفواتير: {{ $unpaidInvoices }}</p>
                        <span class="text-white op-7">{{ $totalInvoices > 0 ? round(($unpaidInvoices / $totalInvoices) * 100) : 0 }} %</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <a href="{{ url('/invoices-paid') }}">
                <div class="card overflow-hidden sales-card bg-success-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <h6 class="mb-3 tx-12 text-white">الفواتير المدفوعة</h6>
                        <h4 class="tx-20 font-weight-bold mb-1 text-white">{{ number_format($paidSum, 2) }}</h4>
                        <p class="mb-0 tx-12 text-white op-10">عدد الفواتير: {{ $paidInvoices }}</p>
                        <span class="text-white op-7">{{ $totalInvoices > 0 ? round(($paidInvoices / $totalInvoices) * 100) : 0 }} %</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <a href="{{ url('/invoices-partially') }}">
                <div class="card overflow-hidden sales-card bg-warning-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <h6 class="mb-3 tx-12 text-white">الفواتير المدفوعة جزئيا</h6>
                        <h4 class="tx-20 font-weight-bold mb-1 text-white">{{ number_format($partialSum, 2) }}</h4>
                        <p class="mb-0 tx-12 text-white op-10">عدد الفواتير: {{ $partialInvoices }}</p>
                        <span class="text-white op-7">{{ $totalInvoices > 0 ? round(($partialInvoices / $totalInvoices) * 100) : 0 }} %</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ URL::asset('assets/js/index.js') }}"></script>
@endsection