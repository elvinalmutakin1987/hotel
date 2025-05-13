<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Itemprice;
use App\Models\Stockout;
use Illuminate\Http\Request;

class StockoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $stockout = Stockout::where('number', 'like', '%' . $request->search . '%')
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
        $stockout->status = $request->status;
        $stockout->save();

        if ($stockout->status == 'Submit') {
        }
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stockout $stockout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stockout $stockout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stockout $stockout)
    {
        //
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
}
