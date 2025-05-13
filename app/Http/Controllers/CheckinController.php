<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Roomtype;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        $check_in = Checkin::whereDate('created_at', $date)->get();
        return view('check_in.index', compact('check_in', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reservation = Reservation::where('room_check_in', 'Off')->whereDate('check_in_date', Carbon::now())->where('status', 'Confirmed')->get();
        return view('check_in.create', compact('reservation'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reservation = Reservation::find($id);
        $room = Room::find($reservation->room_id);
        DB::beginTransaction();
        $reservation->room_check_in = "On";
        $reservation->save();
        $room->status = 'Occupied';
        $room->save();
        $check_in = new Checkin();
        $check_in->reservation_id = $reservation->id;
        $check_in->room_id = $room->id;
        $check_in->save();
        DB::commit();
        return redirect()->route('checkin.index')->with([
            'message' => 'Data saved!'
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
