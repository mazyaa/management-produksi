<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $limit = $request->get('limit', 10);
        $users = $query->orderBy('nama')->paginate($limit)->withQueryString();
        $roles = Role::cases();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::cases();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $request->has('is_active');

        User::create($data);

        return redirect()->route('users.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'User baru berhasil dibuat.',
            'icon' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::cases();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        // Check if editing self and changing role
        if ($user->id === auth()->id() && $data['role'] !== $user->role->value) {
            return back()->withErrors(['role' => 'Anda tidak bisa mengubah role Anda sendiri.'])->withInput();
        }

        if (filled($data['password'] ?? null)) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->has('is_active');

        $user->update($data);

        return redirect()->route('users.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Data user berhasil diperbarui.',
            'icon' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('swal_msg', [
                'title' => 'Gagal!',
                'text' => 'Anda tidak dapat menghapus akun Anda sendiri.',
                'icon' => 'error'
            ]);
        }

        $user->delete();

        return redirect()->route('users.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'User berhasil dihapus.',
            'icon' => 'success'
        ]);
    }

    /**
     * Toggle the active state of a user.
     */
    public function toggleActive(User $user)
    {
        $this->authorize('update', $user);

        if ($user->id === auth()->id()) {
            return back()->with('swal_msg', [
                'title' => 'Gagal!',
                'text' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.',
                'icon' => 'error'
            ]);
        }

        $user->update(['is_active' => !$user->is_active]);

        return redirect()->route('users.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Status aktif user berhasil diubah.',
            'icon' => 'success'
        ]);
    }
}
