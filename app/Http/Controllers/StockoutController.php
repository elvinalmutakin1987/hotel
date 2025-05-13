<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Itemprice;
use App\Models\Stockout;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;

class StockoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth();
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth();
        $stockout = Stockout::query();
        if ($request->start_date && ($request->start_date != 'null' || $request->start_date != 'All')) {
            $stockout = $stockout->where('date', '>=', $start_date);
        }
        if ($request->end_date && ($request->end_date != 'null' || $request->end_date != 'All')) {
            $stockout = $stockout->where('date', '<=', $end_date);
        }
        $stockout = $stockout->where('number', 'like', '%' . $request->search . '%')
            ->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('stockout.index', compact('stockout'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stockout.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
        ]);
        DB::beginTransaction();
        $stockout = new Stockout();
        $stockout->number = Controller::generateCode(6);
        $stockout->date = $request->date;
        $stockout->status = $request->status;
        $stockout->save();
        if ($request->item_id) {
            foreach ($request->item_id as $key => $item_id) {
                $detail[] = [
                    'stockout_id' => $stockout->id,
                    'item_id' => $item_id,
                    'unit' => $request->unit[$key],
                    'qty' => $request->qty ? Controller::number_unformat($request->qty[$key]) : null,
                ];
            }
            $stockout->stockoutdetail()->createMany($detail);
        }
        /**
         * Update stock
         */
        if ($stockout->status == 'Submit') {
            foreach ($stockout->stockoutdetail as $d) {
                $item = Item::find($d->item_id);
                $item->stock = $item->stock - $d->qty;
                $item->save();
            }
        }
        /** End */
        DB::commit();
        return redirect()->route('stockout.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Stockout $stockout)
    {
        return view('stockout.show', compact('stockout'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stockout $stockout)
    {
        return view('stockout.edit', compact('stockout'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stockout $stockout)
    {
        $request->validate([
            'date' => 'required',
        ]);
        DB::beginTransaction();
        $stockout->date = $request->date;
        $stockout->status = $request->status;
        $stockout->save();
        if ($request->item_id) {
            foreach ($request->item_id as $key => $item_id) {
                $detail[] = [
                    'stockout_id' => $stockout->id,
                    'item_id' => $item_id,
                    'unit' => $request->unit[$key],
                    'qty' => $request->qty ? Controller::number_unformat($request->qty[$key]) : null,
                ];
            }
            $stockout->stockoutdetail()->delete();
            $stockout->stockoutdetail()->createMany($detail);
        }
        /**
         * Update stock
         */
        if ($stockout->status == 'Submit') {
            foreach ($stockout->stockoutdetail as $d) {
                $item = Item::find($d->item_id);
                $item->stock = $item->stock - $d->qty;
                $item->save();
            }
        }
        /** End */
        DB::commit();
        return redirect()->route('stockout.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stockout $stockout)
    {
        DB::beginTransaction();
        $stockout->stockoutdetail()->delete();
        $stockout->delete();
        DB::commit();
        return redirect()->route('stockout.index')->with([
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
            return response()->json([
                'item' => $item
            ]);
        }
    }

    public function print(Request $request, Stockout $stockout)
    {
        $pdf = PDF::loadview('stockout.print', compact(
            'stockout'
        ));
        return $pdf->download('stockout-' . $stockout->number  . '.pdf');
    }
}
