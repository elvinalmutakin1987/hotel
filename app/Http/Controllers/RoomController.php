<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Roomtype;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $room_type = Roomtype::all();
        $room = Room::where('number', 'like', '%' . $request->search . '%');
        if ($request->roomtype_id && $request->roomtype_id != 'All') {
            $room = $room->where('roomtype_id', $request->roomtype_id);
        }
        if ($request->status && $request->status != 'All') {
            $room = $room->where('status', $request->status);
        }
        $room = $room->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('rooms.index', compact('room', 'room_type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $room_type = Roomtype::all();
        return view('rooms.create', compact('room_type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'unique:rooms,number'
        ]);
        DB::beginTransaction();
        $room = new Room();
        $room->number = $request->number;
        $room->roomtype_id = $request->roomtype_id;
        $room->status = $request->status;
        $room->save();
        DB::commit();
        return redirect()->route('rooms.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $room_type = Roomtype::all();
        return view('rooms.edit', compact('room', 'room_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'number' => 'unique:rooms,number,' . $room->id . ',id'
        ]);
        DB::beginTransaction();
        $room->number = $request->number;
        $room->roomtype_id = $request->roomtype_id;
        $room->status = $request->status;
        $room->save();
        DB::commit();
        return redirect()->route('rooms.index')->with([
            'message' => 'Data updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        DB::beginTransaction();
        $room->delete();
        DB::commit();
        return redirect()->route('rooms.index')->with([
            'message' => 'Data deleted!'
        ]);
    }
}
