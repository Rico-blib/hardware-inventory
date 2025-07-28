<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;


class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $sales = [];

        if ($request->filled('from') && $request->filled('to')) {
            $request->validate([
                'from' => 'required|date',
                'to' => 'required|date|after_or_equal:from',
            ]);

            $sales = Sale::with('customer')
                ->whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59'])
                ->get();
        }

        return view('reports.sales', compact('sales'));
    }


    public function salesData(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $sales = Sale::with('customer')
            ->whereBetween('created_at', [$request->from, $request->to])
            ->get();

        return response()->json($sales);
    }

    public function purchases(Request $request)
    {
        $query = \App\Models\Purchase::with('supplier');

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        } else {
            $query->whereRaw('0 = 1'); // prevent loading data by default
        }

        $purchases = $query->orderBy('created_at', 'desc')->get();

        return view('reports.purchases', compact('purchases'));
    }


    public function purchasesData(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $purchases = Purchase::with('supplier')
            ->whereBetween('created_at', [$request->from, $request->to])
            ->get();

        return response()->json($purchases);
    }
    public function exportSales($type)
    {
        $sales = Sale::with('customer')->latest()->get();

        switch ($type) {
            case 'csv':
                return Excel::download(new SalesExport($sales), 'sales_report.csv');
            case 'excel':
                return Excel::download(new SalesExport($sales), 'sales_report.xlsx');
            case 'pdf':
                $pdf = Pdf::loadView('exports.sales-pdf', compact('sales'));

                return $pdf->download('sales_report.pdf');
            default:
                return redirect()->back()->with('error', 'Unsupported export format.');
        }
    }



    public function exportPurchases(Request $request, $type)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $query = Purchase::with('supplier')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            });

        $purchases = $query->get();

        if ($type === 'pdf') {
            $pdf = PDF::loadView('exports.purchases_pdf', compact('purchases', 'from', 'to'));
            return $pdf->download('purchases_report.pdf');
        }

        if ($type === 'excel') {
            return Excel::download(new \App\Exports\PurchasesExport($purchases), 'purchases_report.xlsx');
        }

        if ($type === 'csv') {
            return Excel::download(new \App\Exports\PurchasesExport($purchases), 'purchases_report.csv');
        }

        return back()->with('error', 'Invalid export type selected.');
    }
}
