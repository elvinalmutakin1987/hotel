<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Itemprice;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Goodreceipt;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth();
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth();
        $invoice = Invoice::where('number', 'like', '%' . $request->search . '%')->orWhere('supplier_bill', 'like', '%' . $request->search . '%');
        if ($request->start_date && ($request->start_date != 'null' || $request->start_date != 'All')) {
            $invoice = $invoice->where('date', '>=', $start_date);
        }
        if ($request->end_date && ($request->end_date != 'null' || $request->end_date != 'All')) {
            $invoice = $invoice->where('date', '<=', $end_date);
        }
        if ($request->supplier_id && ($request->supplier_id != 'null' || $request->supplier_id != 'All')) {
            $invoice = $invoice->where('supplier_id', $request->supplier_id);
        }
        $invoice = $invoice->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        $supplier = Supplier::all();
        return view('invoice.index', compact('invoice', 'supplier'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $systemsetting = collect(config('systemsetting'));
        $supplier = Supplier::all();
        $goodreceipt = Goodreceipt::where('status', 'Submit')->get();
        return view('invoice.create', compact('supplier', 'goodreceipt', 'systemsetting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'date' => 'required',
            'grand_total' => 'required'
        ]);
        DB::beginTransaction();
        $invoice = new Invoice();
        $invoice->supplier_id = $request->supplier_id;
        $invoice->goodreceipt_id = $request->goodreceipt_id;
        $invoice->number = Controller::generateCode(6);
        $invoice->supplier_bill = $request->supplier_bill;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->total = $request->total ? Controller::number_unformat($request->total) : null;
        $invoice->discount = $request->discount ? Controller::number_unformat($request->discount) : null;
        $invoice->after_discount = $request->after_discount ? Controller::number_unformat($request->after_discount) : null;
        $invoice->tax = $request->tax ? Controller::number_unformat($request->tax) : null;
        $invoice->grand_total = $request->grand_total ? Controller::number_unformat($request->grand_total) : null;
        $invoice->status  = $request->status;
        $invoice->save();
        if ($request->item_id) {
            foreach ($request->item_id as $key => $item_id) {
                $detail[] = [
                    'invoice_id' => $invoice->id,
                    'item_id' => $item_id,
                    'unit' => $request->unit[$key],
                    'price' => $request->price[$key] ? Controller::number_unformat($request->price[$key]) : null,
                    'qty' => $request->qty ? Controller::number_unformat($request->qty[$key]) : null,
                    'sub_total' => $request->sub_total ? Controller::number_unformat($request->sub_total[$key]) : null
                ];
            }
            $invoice->invoicedetail()->createMany($detail);
        }
        DB::commit();
        return redirect()->route('invoice.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $systemsetting = collect(config('systemsetting'));
        return view('invoice.show', compact('invoice', 'systemsetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $systemsetting = collect(config('systemsetting'));
        $supplier = Supplier::all();
        $goodreceipt = Goodreceipt::where('status', 'Submit')->get();
        return view('invoice.edit', compact('invoice', 'goodreceipt', 'supplier', 'systemsetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'supplier_id' => 'required',
            'date' => 'required',
            'grand_total' => 'required'
        ]);
        DB::beginTransaction();
        $invoice->supplier_id = $request->supplier_id;
        $invoice->goodreceipt_id = $request->goodreceipt_id;
        $invoice->number = Controller::generateCode(6);
        $invoice->supplier_bill = $request->supplier_bill;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->total = $request->total ? Controller::number_unformat($request->total) : null;
        $invoice->discount = $request->discount ? Controller::number_unformat($request->discount) : null;
        $invoice->after_discount = $request->after_discount ? Controller::number_unformat($request->after_discount) : null;
        $invoice->tax = $request->tax ? Controller::number_unformat($request->tax) : null;
        $invoice->grand_total = $request->grand_total ? Controller::number_unformat($request->grand_total) : null;
        $invoice->status  = $request->status;
        $invoice->save();
        if ($request->item_id) {
            foreach ($request->item_id as $key => $item_id) {
                $detail[] = [
                    'invoice_id' => $invoice->id,
                    'item_id' => $item_id,
                    'unit' => $request->unit[$key],
                    'price' => $request->price[$key] ? Controller::number_unformat($request->price[$key]) : null,
                    'qty' => $request->qty ? Controller::number_unformat($request->qty[$key]) : null,
                    'sub_total' => $request->sub_total ? Controller::number_unformat($request->sub_total[$key]) : null
                ];
            }
            $invoice->invoicedetail()->delete();
            $invoice->invoicedetail()->createMany($detail);
        }
        DB::commit();
        return redirect()->route('invoice.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        DB::beginTransaction();
        $invoice->invoicedetail()->delete();
        $invoice->delete();
        DB::commit();
        return redirect()->route('invoice.index')->with([
            'message' => 'Data deleted!'
        ]);
    }

    public function get_goodreceipt(Request $request)
    {
        if ($request->ajax()) {
            $term = trim($request->term);
            $goodreceipt = Goodreceipt::selectRaw("id, number as text");
            if ($request->supplier_id != null || $request->supplier_id != 'All' || $request->supplier_id != 'null') {
                $goodreceipt = $goodreceipt->where('supplier_id', $request->supplier_id);
            }
            $goodreceipt = $goodreceipt->where('number', 'like', '%' . $term . '%')
                ->orderBy('number')->simplePaginate(10);
            $total_count = count($goodreceipt);
            $morePages = true;
            $pagination_obj = json_encode($goodreceipt);
            if (empty($goodreceipt->nextPageUrl())) {
                $morePages = false;
            }
            $result = [
                "results" => $goodreceipt->items(),
                "pagination" => [
                    "more" => $morePages
                ],
                "total_count" => $total_count
            ];
            return response()->json($result);
        }
    }

    public function get_goodreceipt_by_id(Request $request)
    {
        if ($request->ajax()) {
            $goodreceipt = Goodreceipt::find($request->goodreceipt_id);
            $view = view('invoice.detail', compact('goodreceipt'))->render();
            return response()->json([
                'view' => $view,
                'goodreceipt' => $goodreceipt
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

    public function print(Request $request, Invoice $invoice)
    {
        $systemsetting = collect(config('systemsetting'));
        $pdf = PDF::loadview('invoice.print', compact(
            'invoice',
            'systemsetting'
        ));
        return $pdf->download('invoice-' . $invoice->number  . '.pdf');
    }
}
