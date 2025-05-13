<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Itemprice;
use App\Models\Purchase;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth();
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth();
        $purchase = Purchase::where('number', 'like', '%' . $request->search . '%');
        if ($request->start_date && ($request->start_date != 'null' || $request->start_date != 'All')) {
            $purchase = $purchase->where('date', '>=', $start_date);
        }
        if ($request->end_date && ($request->end_date != 'null' || $request->end_date != 'All')) {
            $purchase = $purchase->where('date', '<=', $end_date);
        }
        if ($request->supplier_id && ($request->supplier_id != 'null' || $request->supplier_id != 'All')) {
            $purchase = $purchase->where('supplier_id', $request->supplier_id);
        }
        $purchase = $purchase->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        $supplier = Supplier::all();
        return view('purchase.index', compact('purchase', 'supplier'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supplier = Supplier::all();
        return view('purchase.create', compact('supplier'));
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
        $purchase = new Purchase();
        $purchase->supplier_id = $request->supplier_id;
        $purchase->number = Controller::generateCode(6);
        $purchase->date = $request->date;
        $purchase->grand_total = $request->grand_total ? Controller::number_unformat($request->grand_total) : null;
        $purchase->status  = $request->status;
        $purchase->save();
        if ($request->item_id) {
            foreach ($request->item_id as $key => $item_id) {
                $detail[] = [
                    'purchase_id' => $purchase->id,
                    'item_id' => $item_id,
                    'unit' => $request->unit[$key],
                    'price' => $request->price ? Controller::number_unformat($request->price[$key]) : null,
                    'qty' => $request->qty ? Controller::number_unformat($request->qty[$key]) : null,
                    'sub_total' => $request->sub_total ? Controller::number_unformat($request->sub_total[$key]) : null
                ];
            }
            $purchase->purchasedetail()->createMany($detail);
        }
        DB::commit();
        return redirect()->route('purchase.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        return view('purchase.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $supplier = Supplier::all();
        return view('purchase.edit', compact('purchase', 'supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'supplier_id' => 'required',
            'date' => 'required',
            'grand_total' => 'required'
        ]);
        DB::beginTransaction();
        $purchase->supplier_id = $request->supplier_id;
        $purchase->date = $request->date;
        $purchase->grand_total = $request->grand_total ? Controller::number_unformat($request->grand_total) : null;
        $purchase->status  = $request->status;
        $purchase->save();
        if ($request->item_id) {
            foreach ($request->item_id as $key => $item_id) {
                $detail[] = [
                    'purchase_id' => $purchase->id,
                    'item_id' => $item_id,
                    'unit' => $request->unit[$key],
                    'price' => $request->price ? Controller::number_unformat($request->price[$key]) : null,
                    'qty' => $request->qty ? Controller::number_unformat($request->qty[$key]) : null,
                    'sub_total' => $request->sub_total ? Controller::number_unformat($request->sub_total[$key]) : null
                ];
            }
            $purchase->purchasedetail()->delete();
            $purchase->purchasedetail()->createMany($detail);
        }
        DB::commit();
        return redirect()->route('purchase.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        DB::beginTransaction();
        $purchase->purchasedetail()->delete();
        $purchase->delete();
        DB::commit();
        return redirect()->route('purchase.index')->with([
            'message' => 'Data deleted!'
        ]);
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

    public function print(Request $request, Purchase $purchase)
    {
        $pdf = PDF::loadview('purchase.print', compact(
            'purchase'
        ));
        return $pdf->download('purchase-' . $purchase->number  . '.pdf');
    }
}
