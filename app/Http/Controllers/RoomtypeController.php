<?php

namespace App\Http\Controllers;

use App\Models\Roomtype;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RoomtypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $room_type = Roomtype::where('name', 'like', '%' . $request->search . '%')
            ->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('room_types.index', compact('room_type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('room_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'unique:roomtypes,name'
        ]);
        DB::beginTransaction();
        $room_type = new Roomtype();
        $room_type->name = $request->name;
        $room_type->price = Controller::number_unformat($request->price ?? '0');
        $room_type->save();
        DB::commit();
        return redirect()->route('room-types.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Roomtype $room_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Roomtype $room_type)
    {
        return view('room_types.edit', compact('room_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Roomtype $room_type)
    {
        $request->validate([
            'name' => 'unique:roomtypes,name,{$room_type->id},id'
        ]);
        DB::beginTransaction();
        $room_type->name = $request->name;
        $room_type->price = Controller::number_unformat($request->price ?? '0');
        $room_type->save();
        DB::commit();
        return redirect()->route('room-types.index')->with([
            'message' => 'Data updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Roomtype $room_type)
    {
        DB::beginTransaction();
        $room_type->delete();
        DB::commit();
        return redirect()->route('room-types.index')->with([
            'message' => 'Data deleted!'
        ]);
    }
}
