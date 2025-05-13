<?php

namespace App\Http\Controllers;

use App\Models\Additionalitem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdditionalitemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $additional_item = Additionalitem::where('name', 'like', '%' . $request->search . '%')
            ->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('additional_item.index', compact('additional_item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('additional_item.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $additional_item = new Additionalitem;
        $additional_item->name = $request->name;
        $additional_item->price = Controller::number_unformat($request->price ?? '0');
        $additional_item->save();
        DB::commit();
        return redirect()->route('additional-items.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Additionalitem $additionalitem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Additionalitem $additional_item)
    {
        return view('additional_item.edit', compact('additional_item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Additionalitem $additional_item)
    {
        DB::beginTransaction();
        $additional_item->name = $request->name;
        $additional_item->price = Controller::number_unformat($request->price ?? '0');
        $additional_item->save();
        DB::commit();
        return redirect()->route('additional-items.index')->with([
            'message' => 'Data updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Additionalitem $additional_item)
    {
        DB::beginTransaction();
        $additional_item->delete();
        DB::commit();
        return redirect()->route('additional-items.index')->with([
            'message' => 'Data deleted!'
        ]);
    }
}
