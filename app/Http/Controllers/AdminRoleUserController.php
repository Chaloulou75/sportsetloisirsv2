<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminRoleUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'user' => 'required|exists:users,id',
            'role' => 'required|exists:roles,id',
        ]);
        $user = User::findOrFail($request->input('user'));
        $role = Role::findOrFail($request->input('role'));

        $user->roles()->attach($role);

        return to_route('admin.users.index')->with('success', 'Rôle assigné à l\'utilisateur.');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'user' => 'required|exists:users,id',
            'role' => 'required|exists:roles,id',
        ]);
        $user = User::findOrFail($request->input('user'));
        $role = Role::findOrFail($request->input('role'));

        $user->roles()->detach($role);

        return to_route('admin.users.index')->with('success', 'Rôle assigné à l\'utilisateur.');

    }
}
