<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Itemprice;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Goodreceipt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class GoodreceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth();
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth();
        $goodreceipt = Goodreceipt::where('number', 'like', '%' . $request->search . '%');
        if ($request->start_date && $request->start_date != 'null' && $request->start_date != 'All') {
            $goodreceipt = $goodreceipt->where('date', '>=', $start_date);
        }
        if ($request->end_date && ($request->end_date != 'null' && $request->end_date != 'All') {
            $goodreceipt = $goodreceipt->where('date', '<=', $end_date);
        }
        if ($request->supplier_id && ($request->supplier_id != 'null' && $request->supplier_id != 'All') {
            $goodreceipt = $goodreceipt->where('supplier_id', $request->supplier_id);
        }
        $goodreceipt = $goodreceipt->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        $supplier = Supplier::all();
        return view('goodreceipt.index', compact('goodreceipt', 'supplier'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $systemsetting = collect(config('systemsetting'));
        $supplier = Supplier::all();
        $purchase = Purchase::where('status', 'Submit')->get();
        return view('goodreceipt.create', compact('supplier', 'purchase', 'systemsetting'));
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
        $goodreceipt = new Goodreceipt();
        $goodreceipt->supplier_id = $request->supplier_id;
        $goodreceipt->purchase_id = $request->purchase_id;
        $goodreceipt->number = Controller::generateCode(6);
        $goodreceipt->date = $request->date;
        $goodreceipt->total = $request->total ? Controller::number_unformat($request->total) : null;
        $goodreceipt->discount = $request->discount ? Controller::number_unformat($request->discount) : null;
        $goodreceipt->after_discount = $request->after_discount ? Controller::number_unformat($request->after_discount) : null;
        $goodreceipt->tax = $request->tax ? Controller::number_unformat($request->tax) : null;
        $goodreceipt->grand_total = $request->grand_total ? Controller::number_unformat($request->grand_total) : null;
        $goodreceipt->status  = $request->status;
        $goodreceipt->save();
        if ($request->item_id) {
            foreach ($request->item_id as $key => $item_id) {
                $detail[] = [
                    'goodreceipt_id' => $goodreceipt->id,
                    'item_id' => $item_id,
                    'unit' => $request->unit[$key],
                    'price' => $request->price[$key] ? Controller::number_unformat($request->price[$key]) : null,
                    'qty' => $request->qty ? Controller::number_unformat($request->qty[$key]) : null,
                    'sub_total' => $request->sub_total ? Controller::number_unformat($request->sub_total[$key]) : null
                ];
            }
            $goodreceipt->goodreceiptdetail()->createMany($detail);
        }
        /**
         * Update stock
         */
        if ($goodreceipt->status == 'Submit') {
            foreach ($goodreceipt->goodreceiptdetail as $d) {
                $item = Item::find($d->item_id);
                $item->stock = $item->stock + $d->qty;
                $item->save();
            }
        }
        /** End */
        DB::commit();
        return redirect()->route('goodreceipt.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Goodreceipt $goodreceipt)
    {
        $systemsetting = collect(config('systemsetting'));
        return view('goodreceipt.show', compact('goodreceipt', 'systemsetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Goodreceipt $goodreceipt)
    {
        $systemsetting = collect(config('systemsetting'));
        $supplier = Supplier::all();
        $purchase = Purchase::where('status', 'Submit')->get();
        return view('goodreceipt.edit', compact('goodreceipt', 'purchase', 'supplier', 'systemsetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Goodreceipt $goodreceipt)
    {
        $request->validate([
            'supplier_id' => 'required',
            'date' => 'required',
            'grand_total' => 'required'
        ]);
        DB::beginTransaction();
        $goodreceipt->supplier_id = $request->supplier_id;
        $goodreceipt->purchase_id = $request->purchase_id;
        $goodreceipt->date = $request->date;
        $goodreceipt->total = $request->total ? Controller::number_unformat($request->total) : null;
        $goodreceipt->discount = $request->discount ? Controller::number_unformat($request->discount) : null;
        $goodreceipt->after_discount = $request->after_discount ? Controller::number_unformat($request->after_discount) : null;
        $goodreceipt->tax = $request->tax ? Controller::number_unformat($request->tax) : null;
        $goodreceipt->grand_total = $request->grand_total ? Controller::number_unformat($request->grand_total) : null;
        $goodreceipt->status  = $request->status;
        $goodreceipt->save();
        if ($request->item_id) {
            foreach ($request->item_id as $key => $item_id) {
                $detail[] = [
                    'goodreceipt_id' => $goodreceipt->id,
                    'item_id' => $item_id,
                    'unit' => $request->unit[$key],
                    'price' => $request->price[$key] ? Controller::number_unformat($request->price[$key]) : null,
                    'qty' => $request->qty ? Controller::number_unformat($request->qty[$key]) : null,
                    'sub_total' => $request->sub_total ? Controller::number_unformat($request->sub_total[$key]) : null
                ];
            }
            $goodreceipt->goodreceiptdetail()->delete();
            $goodreceipt->goodreceiptdetail()->createMany($detail);
        }
        /**
         * Update stock
         */
        if ($goodreceipt->status == 'Submit') {
            foreach ($goodreceipt->goodreceiptdetail as $d) {
                $item = Item::find($d->item_id);
                $item->stock = $item->stock + $d->qty;
                $item->save();
            }
        }
        /** End */
        DB::commit();
        return redirect()->route('goodreceipt.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Goodreceipt $goodreceipt)
    {
        DB::beginTransaction();
        $goodreceipt->goodreceiptdetail()->delete();
        $goodreceipt->delete();
        DB::commit();
        return redirect()->route('goodreceipt.index')->with([
            'message' => 'Data deleted!'
        ]);
    }

    public function get_purchase(Request $request)
    {
        if ($request->ajax()) {
            $term = trim($request->term);
            $purchase = Purchase::selectRaw("id, number as text");
            if ($request->supplier_id != null || $request->supplier_id != 'All' || $request->supplier_id != 'null') {
                $purchase = $purchase->where('supplier_id', $request->supplier_id);
            }
            $purchase = $purchase->where('number', 'like', '%' . $term . '%')
                ->orderBy('number')->simplePaginate(10);
            $total_count = count($purchase);
            $morePages = true;
            $pagination_obj = json_encode($purchase);
            if (empty($purchase->nextPageUrl())) {
                $morePages = false;
            }
            $result = [
                "results" => $purchase->items(),
                "pagination" => [
                    "more" => $morePages
                ],
                "total_count" => $total_count
            ];
            return response()->json($result);
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

    public function print(Request $request, Goodreceipt $goodreceipt)
    {
        $systemsetting = collect(config('systemsetting'));
        $pdf = PDF::loadview('goodreceipt.print', compact(
            'goodreceipt',
            'systemsetting'
        ));
        return $pdf->download('goodreceipt-' . $goodreceipt->number  . '.pdf');
    }
}
