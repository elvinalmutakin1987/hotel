<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Roomtype;
use App\Models\Roomactivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CleaningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $room = Room::all();
        return view('cleaning.index', compact('room'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::find($id);
        return view('cleaning.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required'
        ]);
        DB::beginTransaction();
        $room = Room::find($id);
        $room->status = $request->status;
        $room->save();
        $room_activities = new Roomactivity();
        $room_activities->room_id = $id;
        $room_activities->status = $request->status;
        $room_activities->notes = $request->notes;
        $room_activities->save();
        DB::commit();
        return redirect()->route('cleaning.index')->with([
            'message' => 'Data updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
