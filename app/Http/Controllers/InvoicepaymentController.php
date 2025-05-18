<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Itemprice;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\Invoicepayment;
use App\Models\Invoicepaymentdetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;


class InvoicepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth();
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth();
        $invoicepayment = Invoicepayment::where('number', 'like', '%' . $request->search . '%');
        if ($request->start_date && ($request->start_date != 'null' || $request->start_date != 'All')) {
            $invoicepayment = $invoicepayment->where('date', '>=', $start_date);
        }
        if ($request->end_date && ($request->end_date != 'null' || $request->end_date != 'All')) {
            $invoicepayment = $invoicepayment->where('date', '<=', $end_date);
        }
        if ($request->supplier_id && ($request->supplier_id != 'null' || $request->supplier_id != 'All')) {
            $invoicepayment = $invoicepayment->where('supplier_id', $request->supplier_id);
        }
        $invoicepayment = $invoicepayment->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        $supplier = Supplier::all();
        return view('invoicepayment.index', compact('invoicepayment', 'supplier'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $systemsetting = collect(config('systemsetting'));
        $supplier = Supplier::all();
        $invoice = Invoice::where('status', 'Submit')->get();
        return view('invoicepayment.create', compact('supplier', 'invoice', 'systemsetting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'invoice_id' => 'required',
            'date' => 'required',
            'payment_total' => 'required'
        ]);
        if ($request->payment_method == 'Bank Transfer') {
            $request->validate([
                'supplier_id' => 'required',
                'invoice_id' => 'required',
                'date' => 'required',
                'payment_total' => 'required',
                'bank_name' => 'required',
                'bank_account' => 'required',
                'transaction_number' => 'required'
            ]);
        }
        DB::beginTransaction();
        $invoicepayment = new Invoicepayment();
        $invoicepayment->supplier_id = $request->supplier_id;
        $invoicepayment->invoice_id = $request->invoice_id;
        $invoicepayment->number = Controller::generateCode(6);
        $invoicepayment->date = $request->date;
        $invoicepayment->payment_method = $request->payment_method;
        $invoicepayment->bank_name = $request->bank_name;
        $invoicepayment->bank_account = $request->bank_account;
        $invoicepayment->transaction_number = $request->transaction_number;
        $invoicepayment->payment_total = $request->payment_total ? Controller::number_unformat($request->payment_total) : null;
        $invoicepayment->status  = $request->status;
        $invoicepayment->save();
        DB::commit();
        return redirect()->route('invoicepayment.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoicepayment $invoicepayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoicepayment $invoicepayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoicepayment $invoicepayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoicepayment $invoicepayment)
    {
        DB::beginTransaction();
        $invoicepayment->delete();
        DB::commit();
        return redirect()->route('invoicepayment.index')->with([
            'message' => 'Data deleted!'
        ]);
    }

    public function get_invoice(Request $request)
    {
        if ($request->ajax()) {
            $term = trim($request->term);
            $invoice = Invoice::selectRaw("id, number as text");
            if ($request->supplier_id != null || $request->supplier_id != 'All' || $request->supplier_id != 'null') {
                $invoice = $invoice->where('supplier_id', $request->supplier_id);
            }
            $invoice = $invoice->where('number', 'like', '%' . $term . '%')
                ->orderBy('number')->simplePaginate(10);
            $total_count = count($invoice);
            $morePages = true;
            $pagination_obj = json_encode($invoice);
            if (empty($invoice->nextPageUrl())) {
                $morePages = false;
            }
            $result = [
                "results" => $invoice->items(),
                "pagination" => [
                    "more" => $morePages
                ],
                "total_count" => $total_count
            ];
            return response()->json($result);
        }
    }

    public function get_invoice_by_id(Request $request)
    {
        if ($request->ajax()) {
            $invoice = Invoice::find($request->invoice_id);
            $view = view('invoicepayment.detail', compact('invoice'))->render();
            return response()->json([
                'view' => $view,
                'invoice' => $invoice
            ]);
        }
    }

    public function get_item(Request $request)
    {
        if ($request->ajax()) {
            $term = trim($request->term);
            $item = Item::selectRaw("id, name as text")
                ->where('name', 'like', '%' . $term . '%')
                ->orderBy('name')->simplePaginate(10);
            $total_count = count($item);
            $morePages = true;
            $pagination_obj = json_encode($item);
            if (empty($item->nextPageUrl())) {
                $morePages = false;
            }
            $result = [
                "results" => $item->items(),
                "pagination" => [
                    "more" => $morePages
                ],
                "total_count" => $total_count
            ];
            return response()->json($result);
        }
    }

    public function get_item_by_id(Request $request)
    {
        if ($request->ajax()) {
            $item = Item::find($request->item_id);
            $itemprice = Itemprice::where('item_id', $request->item_id)->where('supplier_id', $request->supplier_id)->first();
            return response()->json([
                'item' => $item,
                'itemprice' => $itemprice
            ]);
        }
    }

    public function print(Request $request, Invoicepayment $invoicepayment)
    {
        $systemsetting = collect(config('systemsetting'));
        $pdf = PDF::loadview('invoicepayment.print', compact(
            'invoicepayment',
            'systemsetting'
        ));
        return $pdf->download('invoicepayment-' . $invoicepayment->number  . '.pdf');
    }
}
