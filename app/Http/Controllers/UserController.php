<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::where('username', 'like', '%' . $request->search . '%')
            ->paginate(10, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('users.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = Role::all();
        return view('users.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = fake()->unique()->safeEmail();
        $user->email_verified_at = now();
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(10);
        $user->save();
        $user->assignRole($request->role);
        DB::commit();
        return redirect()->route('user.index')->with([
            'message' => 'Data saved!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $role = Role::all();
        return view('users.edit', compact('user', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        DB::beginTransaction();
        $user->username = $request->username;
        $user->name = $request->name;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $user->syncRoles($request->role);
        DB::commit();
        return redirect()->route('user.index')->with([
            'message' => 'Data updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        $user->syncRoles();
        $user->delete();
        DB::commit();
        return redirect()->route('user.index')->with([
            'message' => 'Data deleted!'
        ]);
    }
}
