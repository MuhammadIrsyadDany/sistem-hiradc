<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('users.manage'), 403);

        $users = User::with('roles')
            ->latest()
            ->paginate(10);

        return view('master.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('users.manage'), 403);

        $roles = Role::all();

        return view('master.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('users.manage'), 403);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nip'      => 'nullable|string|max:50',
            'jabatan'  => 'nullable|string|max:100',
            'no_hp'    => 'nullable|string|max:20',
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,name',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'nip'       => $validated['nip'],
            'jabatan'   => $validated['jabatan'],
            'no_hp'     => $validated['no_hp'],
            'is_active' => true,
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('master.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('users.manage'), 403);

        $roles = Role::all();

        return view('master.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        abort_if(Gate::denies('users.manage'), 403);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'nip'      => 'nullable|string|max:50',
            'jabatan'  => 'nullable|string|max:100',
            'no_hp'    => 'nullable|string|max:20',
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,name',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'nip'       => $validated['nip'],
            'jabatan'   => $validated['jabatan'],
            'no_hp'     => $validated['no_hp'],
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->syncRoles($validated['roles']);

        return redirect()->route('master.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('users.manage'), 403);

        if ($user->id === auth()->id()) {
            return redirect()->route('master.users.index')
                ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->update(['is_active' => false]);

        return redirect()->route('master.users.index')
            ->with('success', 'User berhasil dinonaktifkan.');
    }
}
