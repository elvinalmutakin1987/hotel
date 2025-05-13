<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $item = Item::where('name', 'like', '%' . $request->search . '%')
            ->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('stocks.index', compact('item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unit = collect(config('itemunit'));
        $supplier = Supplier::all();
        return view('stocks.create', compact('unit', 'supplier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:items,name'
        ]);
        DB::beginTransaction();
        $item = new Item();
        $item->name = $request->name;
        $item->unit = $request->unit;
        $item->stock = $request->stock ? Controller::number_unformat($request->stock) : 0;
        $item->save();
        if ($request->supplier_id) {
            foreach ($request->supplier_id as $key => $supplier_id) {
                $detail[] = [
                    'supplier_id' => $supplier_id,
                    'item_id' => $item->id,
                    // 'unit' => $request->item_unit[$key],
                    'price' => $request->price[$key] ? Controller::number_unformat($request->price[$key]) : 0
                ];
            }
            $item->itemprice()->createMany($detail);
        }
        DB::commit();
        return redirect()->route('stocks.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $stock)
    {
        $item = $stock;
        $unit = collect(config('itemunit'));
        $supplier = Supplier::all();
        return view('stocks.edit', compact(
            'item',
            'unit',
            'supplier'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $stock)
    {
        $item = $stock;
        $request->validate([
            'name' => 'required|unique:items,name,' . $item->id . ',id'
        ]);
        $item = $stock;
        $item->name = $request->name;
        $item->unit = $request->unit;
        $item->stock = $request->stock ? Controller::number_unformat($request->stock) : 0;
        $item->save();
        if ($request->supplier_id) {
            foreach ($request->supplier_id as $key => $supplier_id) {
                $detail[] = [
                    'supplier_id' => $supplier_id,
                    'item_id' => $item->id,
                    // 'unit' => $request->item_unit[$key],
                    'price' => $request->price[$key] ? Controller::number_unformat($request->price[$key]) : 0
                ];
            }
            $item->itemprice()->delete();
            $item->itemprice()->createMany($detail);
        }
        DB::commit();
        return redirect()->route('stocks.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $stock)
    {
        DB::beginTransaction();
        $stock->delete();
        DB::commit();
        return redirect()->route('stocks.index')->with([
            'message' => 'Data deleted!'
        ]);
    }
}
