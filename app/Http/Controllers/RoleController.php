<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = Role::where('name', 'like', '%' . $request->search . '%')
            ->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('roles.index', compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        if ($request->guests) $role->givePermissionTo($request->guests);
        if ($request->reservations) $role->givePermissionTo($request->reservations);
        if ($request->checkin) $role->givePermissionTo($request->checkin);
        if ($request->checkout) $role->givePermissionTo($request->checkout);
        if ($request->roomtypes) $role->givePermissionTo($request->roomtypes);
        if ($request->rooms) $role->givePermissionTo($request->rooms);
        if ($request->cleaning) $role->givePermissionTo($request->cleaning);
        if ($request->laundry) $role->givePermissionTo($request->laundry);
        if ($request->additionalitems) $role->givePermissionTo($request->additionalitems);
        if ($request->contracts) $role->givePermissionTo($request->contracts);
        if ($request->billings) $role->givePermissionTo($request->billings);
        if ($request->spaceforrent) $role->givePermissionTo($request->spaceforrent);
        if ($request->costlist) $role->givePermissionTo($request->costlist);
        if ($request->supplier) $role->givePermissionTo($request->supplier);
        if ($request->purchase) $role->givePermissionTo($request->purchase);
        if ($request->goodreceipt) $role->givePermissionTo($request->goodreceipt);
        if ($request->dispatching) $role->givePermissionTo($request->dispatching);
        if ($request->inventory) $role->givePermissionTo($request->inventory);
        if ($request->stockopname) $role->givePermissionTo($request->stockopname);
        if ($request->transactions) $role->givePermissionTo($request->transactions);
        if ($request->invoices) $role->givePermissionTo($request->invoices);
        if ($request->expenses) $role->givePermissionTo($request->expenses);
        if ($request->chartofaccounts) $role->givePermissionTo($request->chartofaccounts);
        if ($request->finansialreports) $role->givePermissionTo($request->finansialreports);
        if ($request->journalentries) $role->givePermissionTo($request->journalentries);
        if ($request->balancesheet) $role->givePermissionTo($request->balancesheet);
        if ($request->cashflow) $role->givePermissionTo($request->cashflow);
        if ($request->profitlost) $role->givePermissionTo($request->profitlost);
        if ($request->staff) $role->givePermissionTo($request->staff);
        if ($request->user) $role->givePermissionTo($request->user);
        if ($request->role) $role->givePermissionTo($request->role);
        DB::commit();
        return redirect()->route('role.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        DB::beginTransaction();
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions();
        if ($request->guests) $role->givePermissionTo($request->guests);
        if ($request->reservations) $role->givePermissionTo($request->reservations);
        if ($request->checkin) $role->givePermissionTo($request->checkin);
        if ($request->checkout) $role->givePermissionTo($request->checkout);
        if ($request->roomtypes) $role->givePermissionTo($request->roomtypes);
        if ($request->rooms) $role->givePermissionTo($request->rooms);
        if ($request->cleaning) $role->givePermissionTo($request->cleaning);
        if ($request->laundry) $role->givePermissionTo($request->laundry);
        if ($request->additionalitems) $role->givePermissionTo($request->additionalitems);
        if ($request->contracts) $role->givePermissionTo($request->contracts);
        if ($request->billings) $role->givePermissionTo($request->billings);
        if ($request->spaceforrent) $role->givePermissionTo($request->spaceforrent);
        if ($request->costlist) $role->givePermissionTo($request->costlist);
        if ($request->supplier) $role->givePermissionTo($request->supplier);
        if ($request->purchase) $role->givePermissionTo($request->purchase);
        if ($request->goodreceipt) $role->givePermissionTo($request->goodreceipt);
        if ($request->dispatching) $role->givePermissionTo($request->dispatching);
        if ($request->inventory) $role->givePermissionTo($request->inventory);
        if ($request->stockopname) $role->givePermissionTo($request->stockopname);
        if ($request->transactions) $role->givePermissionTo($request->transactions);
        if ($request->invoices) $role->givePermissionTo($request->invoices);
        if ($request->expenses) $role->givePermissionTo($request->expenses);
        if ($request->chartofaccounts) $role->givePermissionTo($request->chartofaccounts);
        if ($request->finansialreports) $role->givePermissionTo($request->finansialreports);
        if ($request->journalentries) $role->givePermissionTo($request->journalentries);
        if ($request->balancesheet) $role->givePermissionTo($request->balancesheet);
        if ($request->cashflow) $role->givePermissionTo($request->cashflow);
        if ($request->profitlost) $role->givePermissionTo($request->profitlost);
        if ($request->staff) $role->givePermissionTo($request->staff);
        if ($request->user) $role->givePermissionTo($request->user);
        if ($request->role) $role->givePermissionTo($request->role);
        DB::commit();
        return redirect()->route('role.index')->with([
            'message' => 'Data updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        DB::beginTransaction();
        $role->syncPermissions();
        $role->delete();
        DB::commit();
        return redirect()->route('role.index')->with([
            'message' => 'Data deleted!'
        ]);
    }
}
