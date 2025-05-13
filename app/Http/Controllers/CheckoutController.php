<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Roomtype;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        $check_out = Checkout::whereDate('created_at', $date)->get();
        return view('check_out.index', compact('check_out', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reservation = Reservation::where('room_check_in', 'On')->where('room_check_out', 'Out')->where('status', 'Confirmed')->get();
        return view('check_out.create', compact('reservation'));
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
        $reservation->room_check_out = "On";
        $reservation->save();
        $room->status = 'Check-out';
        $room->save();
        $check_out = new Checkout();
        $check_out->reservation_id = $reservation->id;
        $check_out->room_id = $room->id;
        $check_out->save();
        DB::commit();
        return redirect()->route('checkout.index')->with([
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
