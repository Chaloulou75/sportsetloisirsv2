<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminRoleUserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user.id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,id',
        ]);

        $userId = $validated['user']['id'];
        $roleId = $validated['role'];

        $user = User::findOrFail($userId);
        $role = Role::findOrFail($roleId);

        $userRoles = $user->roles()->pluck('role_id');
        if ($userRoles->contains($validated['role'])) {
            return to_route('admin.users.index')->withErrors(['user' => 'Cet utilisateur est déjà assigné à ce rôle.']);
        }

        $user->roles()->attach($role);

        return to_route('admin.users.index')->with('success', 'Rôle assigné à l\'utilisateur.');
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
        $validated = $request->validate([
            'user.id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,id',
        ]);

        $userId = $validated['user']['id'];
        $roleId = $validated['role'];

        $user = User::findOrFail($userId);
        $role = Role::findOrFail($roleId);

        $user->roles()->detach($role);

        return to_route('admin.users.index')->with('success', 'Rôle assigné à l\'utilisateur.');

    }
}
