<?php

namespace App\Http\Controllers;

use App\Models\Additionalitem;
use App\Models\Checkin;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Reservationitem;
use App\Models\Room;
use App\Models\Roomtype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Ulid;

class ReservationController extends Controller
{
    //bin2hex(random_bytes(5))
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');
        $search = $request->search;
        $reservation = Reservation::whereYear('check_in_date', $year)->whereMonth('check_in_date', $month);
        if ($request->search) {
            $reservation = $reservation->whereIn('guest_id', function ($q) use ($search) {
                $q->select('id')
                    ->from('guests')
                    ->where('name', 'like', '%' . $search . '%');
            });
        }
        $reservation = $reservation->orderBy('id', 'desc');
        $reservation = $reservation->get();
        $room = Room::all();
        $room_type = Roomtype::all();
        $view = $request->view == 'list' ? 'reservations.list' : 'reservations.index';
        return view($view, compact(
            'reservation',
            'year',
            'month',
            'room_type',
            'room'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $room = Room::all();
        $additional_item = Additionalitem::all();
        $view = $request->view;
        return view('reservations.create', compact('room', 'additional_item', 'view'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = [
            'name' => 'required',
            'id_card_number' => 'required',
            'phone' => 'required',
            'room_id' => 'required',
            'check_in_date' => 'required',
            'check_out_date' => 'required'
        ];
        if ($request->payment_method != 'Cash') {
            $validate = [
                'name' => 'required',
                'id_card_number' => 'required',
                'room_id' => 'required',
                'check_in_date' => 'required',
                'check_out_date' => 'required',
                'bank_name' => 'required',
                'account_number' => 'required',
                'transaction_id' => 'required'
            ];
        }
        $request->validate($validate);
        DB::beginTransaction();
        $guest = Guest::where('id_card_number', $request->id_card_number)->first();
        if (!$guest) {
            $guest = new Guest();
            $guest->id_card_number = $request->id_card_number;
        }
        $guest->name = $request->name;
        $guest->address = $request->address;
        $guest->city = $request->city;
        $guest->phone = $request->phone;
        $guest->save();
        $reservation = new Reservation();
        $reservation->number = Controller::generateCode(6);
        $reservation->guest_id = $guest->id;
        $reservation->room_id = $request->room_id;
        $reservation->price = Room::find($request->room_id)->price ?? 0;
        $reservation->check_in_date = $request->check_in_date;
        $reservation->check_out_date = $request->check_out_date;
        $reservation->amount = Controller::number_unformat($request->amount);
        $reservation->payment_method = $request->payment_method;
        $reservation->bank_name = $request->bank_name;
        $reservation->account_number = $request->account_number;
        $reservation->transaction_id = $request->transaction_id;
        $reservation->status = $request->status;
        $reservation->room_check_in = "Off";
        $reservation->save();
        if ($request->additionalitem_id) {
            foreach ($request->additionalitem_id as $key => $additionalitem_id) {
                $detail[] = [
                    'additionalitem_id' => $additionalitem_id,
                    'reservation_id' => $reservation->id,
                    'price' => Additionalitem::find($additionalitem_id)->price ?? 0,
                    'qty' => Controller::number_unformat($request->qty[$key])
                ];
            }
            $reservation->reservationitem()->createMany($detail);
        }
        if ($request->status == 'Confirmed') {
        }
        DB::commit();
        return redirect()->route('reservations.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $room = Room::all();
        $additional_item = Additionalitem::all();
        $reservation_item = Reservationitem::where('reservation_id', $reservation->id)->get();
        return view('reservations.show', compact('reservation', 'room', 'additional_item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        $room = Room::all();
        $additional_item = Additionalitem::all();
        $reservation_item = Reservationitem::where('reservation_id', $reservation->id)->get();
        return view('reservations.edit', compact('reservation', 'room', 'additional_item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        DB::beginTransaction();
        $reservation->status = 'Confirmed';
        $reservation->save();
        DB::commit();
        return redirect()->route('reservations.index', ['view' => 'list'])->with([
            'message' => 'Reservation is confirmed!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        DB::beginTransaction();
        $reservation->status = 'Canceled';
        $reservation->save();
        DB::commit();
        return redirect()->route('reservations.index', ['view' => 'list'])->with([
            'message' => 'Reservation is canceled!'
        ]);
    }

    public function get_rooms(Request $request)
    {
        $check_in_date = $request->check_in_date;
        $check_out_date = $request->check_out_date;
        $room_types = Roomtype::all();
        $rooms = Room::whereNotExists(function ($query) use ($check_in_date, $check_out_date) {
            $query->select(DB::raw(1))
                ->from('reservations')
                ->whereColumn('rooms.id', 'reservations.room_id')
                ->where(function ($q) use ($check_in_date, $check_out_date) {
                    $q->whereDate('check_in_date', '<=', $check_in_date);
                    $q->whereDate('check_out_date', '>', $check_in_date);
                });
        })->get();
        $view = view('reservations.room-list', compact(
            'check_in_date',
            'check_out_date',
            'rooms',
            'room_types'
        ))->render();
        return response()->json([
            'status' => 'success',
            'data' => $view,
            'message' => 'success'
        ]);
    }

    public function get_room_by_id(Request $request)
    {
        $room = Room::find($request->room_id);
        return response()->json([
            'status' => 'success',
            'data' => $room,
            'price' => $room->roomtype->price,
            'message' => 'success'
        ]);
    }

    public function confirm(Reservation $reservation)
    {
        DB::beginTransaction();
        $reservation->status = 'Confirmed';
        $reservation->save();
        DB::commit();
        return redirect()->route('reservations.index', ['view' => 'list'])->with([
            'message' => 'Reservation is confirmed!'
        ]);
    }

    public function cancel(Reservation $reservation)
    {
        DB::beginTransaction();
        $reservation->status = 'Canceled';
        $reservation->save();
        DB::commit();
        return redirect()->route('reservations.index', ['view' => 'list'])->with([
            'message' => 'Reservation is canceled!'
        ]);
    }

    public function check_in(Request $request, string $id)
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
        return redirect()->route('reservations.index')->with([
            'message' => 'Data saved!'
        ]);
    }
}
