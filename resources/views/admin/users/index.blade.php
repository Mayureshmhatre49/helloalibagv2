@extends('layouts.admin')
@section('page-title', 'User Management')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
    {{-- Header & Filters --}}
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary">
            </div>
            
            <div class="flex gap-4">
                <select name="role" onchange="this.form.submit()" 
                        class="pl-4 pr-10 py-2.5 rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary bg-white">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                
                @if(request()->anyFilled(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors text-sm font-bold flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-[11px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Listings</th>
                    <th class="px-6 py-4">Joined</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-100 flex-shrink-0">
                                    <img src="{{ $user->getAvatarUrl() }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 leading-none mb-1">{{ $user->name }}</p>
                                    <p class="text-[11px] text-slate-500 font-medium">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $roleColors = [
                                    'admin' => 'bg-charcoal text-white',
                                    'owner' => 'bg-primary/10 text-primary',
                                    'user' => 'bg-slate-100 text-slate-600',
                                ];
                            @endphp
                            <span class="text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider {{ isset($user->role) ? ($roleColors[$user->role->slug] ?? 'bg-slate-100 text-slate-600') : 'bg-slate-100 text-slate-400' }}">
                                {{ $user->role->name ?? 'No Role' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-700">{{ $user->listings_count ?? $user->listings()->count() }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-slate-500 font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-primary hover:bg-primary/5 transition-colors" title="Toggle Status">
                                        <span class="material-symbols-outlined text-[20px]">block</span>
                                    </button>
                                </form>
                                @if(!($user->role?->slug === 'admin'))
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors" title="Delete User">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="max-w-xs mx-auto">
                                <span class="material-symbols-outlined text-4xl text-slate-200 mb-2">person_search</span>
                                <p class="text-sm text-slate-500 italic">No users found matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
