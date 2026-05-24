<x-app-layout>
    @section('title', 'Kelola User')
    @section('page-title', 'Manajemen User')

    <x-page-header title="Manajemen Pengguna" subtitle="Daftar akun pengguna, kelola role dan status keaktifan operator.">
        <button type="button"
            @click="$dispatch('open-modal', { name: 'create-user' })"
            class="btn-primary flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah User Baru
        </button>
    </x-page-header>

    <x-filter-section>
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <x-form-input
                label="Cari Pengguna"
                name="search"
                placeholder="Nama, username, email..."
                value="{{ request('search') }}"
            />

            <x-form-select label="Filter Role" name="role" placeholder="Semua Role">
                @foreach($roles as $role)
                    <option value="{{ $role->value }}" {{ request('role') === $role->value ? 'selected' : '' }}>
                        {{ $role->label() }}
                    </option>
                @endforeach
            </x-form-select>

            <x-form-select label="Filter Status" name="status" placeholder="Semua Status">
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
            </x-form-select>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 btn-primary py-2.5">Terapkan</button>
                <a href="{{ route('users.index') }}" class="btn-secondary py-2.5 inline-flex justify-center items-center">Reset</a>
            </div>
        </form>
    </x-filter-section>

    <x-card :padding="false">
        @if($users->count() > 0)
            <x-data-table :headers="['Nama', 'Username / Email', 'Role', 'Status', 'Aksi']">
                @foreach($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $user->nama }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-800 text-xs font-semibold">{{ $user->username }}</div>
                            <div class="text-slate-400 text-[11px] font-medium">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider {{ $user->isAdmin() ? 'bg-primary-50 text-primary-700 border border-primary-200/50' : ($user->isLeader() ? 'bg-secondary-50 text-secondary-700 border border-secondary-200/50' : ($user->isOperator() ? 'bg-slate-50 text-slate-700 border border-slate-200/50' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/50')) }}">
                                {{ $user->role->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('users.toggle-active', $user->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border transition-all duration-150 {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-250 hover:bg-emerald-100' : 'bg-red-50 text-red-700 border-red-250 hover:bg-red-100' }}"
                                    title="Klik untuk mengubah status"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                >
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                    @click="$dispatch('open-modal', { name: 'edit-user-{{ $user->id }}' })"
                                    class="text-slate-400 hover:text-primary-600 transition-colors p-1.5 rounded-lg border border-slate-200 hover:bg-white hover:shadow-sm"
                                    title="Edit Data User"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                @if($user->id !== auth()->id())
                                    <x-confirm-delete
                                        :action="route('users.destroy', $user->id)"
                                        title="Hapus Pengguna?"
                                        text="Akun {{ $user->nama }} akan dihapus secara permanen dari sistem."
                                        class="text-slate-400 hover:text-red-650 transition-colors p-1.5 rounded-lg border border-slate-200 hover:bg-white hover:shadow-sm"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </x-confirm-delete>
                                @endif
                            </div>
                        </td>
                    </tr>

                @endforeach
            </x-data-table>

            @foreach($users as $user)
                @include('users.partials.edit', ['item' => $user])
            @endforeach

            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/20">
                <div class="flex items-center gap-2 text-sm">
                    <form method="GET" action="{{ route('users.index') }}">
                        @foreach(request()->except('limit', 'page') as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $k => $v)
                                    <input type="hidden" name="{{ $key }}[{{ $k }}]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <label class="font-medium text-slate-600">Tampilkan</label>
                        <select name="limit" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-300 text-slate-700 font-semibold py-1.5 px-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-slate-500">data</span>
                    </form>
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @else
            <div class="p-6">
                <x-empty-state
                    title="User Tidak Ditemukan"
                    message="Tidak ada akun user yang cocok dengan kriteria pencarian Anda."
                >
                    <button type="button"
                        @click="$dispatch('open-modal', { name: 'create-user' })"
                        class="btn-primary btn-sm flex items-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah User
                    </button>
                </x-empty-state>
            </div>
        @endif
    </x-card>

    @include('users.partials.add')
</x-app-layout>
