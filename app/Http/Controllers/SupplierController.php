<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $supplier = Supplier::where('name', 'like', '%' . $request->search . '%')
            ->orWhere('contact', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%')
            ->orWhere('address', 'like', '%' . $request->search . '%')
            ->orWhere('city', 'like', '%' . $request->search . '%')
            ->orWhere('tax_id', 'like', '%' . $request->search . '%')
            ->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('supplier.index', compact('supplier'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->contact = $request->contact;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->city = $request->city;
        $supplier->tax_id = $request->tax_id;
        $supplier->save();
        DB::commit();
        return redirect()->route('supplier.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        DB::beginTransaction();
        $supplier->name = $request->name;
        $supplier->contact = $request->contact;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->city = $request->city;
        $supplier->tax_id = $request->tax_id;
        $supplier->save();
        DB::commit();
        return redirect()->route('supplier.index')->with([
            'message' => 'Data updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        DB::beginTransaction();
        $supplier->delete();
        DB::commit();
        return redirect()->route('supplier.index')->with([
            'message' => 'Data deleted!'
        ]);
    }
}
