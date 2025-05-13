<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $guest = Guest::where('name', 'like', '%' . $request->search . '%')
            ->orWhere('name', 'like', '%' . $request->search . '%')
            ->orWhere('id_card_number', 'like', '%' . $request->search . '%')
            ->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('guests.index', compact('guest'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'id_card_number' => 'required',
            'phone' => 'required'
        ]);
        DB::beginTransaction();
        $guest = new Guest();
        $guest->name = $request->name;
        $guest->id_card_number = $request->id_card_number;
        $guest->id_card_image = $request->id_card_image;
        $guest->address = $request->address;
        $guest->city = $request->city;
        $guest->phone = $request->phone;
        $guest->save();
        DB::commit();
        return redirect()->route('guests.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Guest $guest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guest $guest)
    {
        return view('guests.edit', compact('guest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest)
    {
        $request->validate([
            'name' => 'required',
            'id_card_number' => 'required',
            'phone' => 'required'
        ]);
        DB::beginTransaction();
        $guest->name = $request->name;
        $guest->id_card_number = $request->id_card_number;
        $guest->id_card_image = $request->id_card_image;
        $guest->address = $request->address;
        $guest->city = $request->city;
        $guest->phone = $request->phone;
        $guest->save();
        DB::commit();
        return redirect()->route('guests.index')->with([
            'message' => 'Data updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
        DB::beginTransaction();
        $guest->delete();
        DB::commit();
        return redirect()->route('guests.index')->with([
            'message' => 'Data deleted!'
        ]);
    }
}
