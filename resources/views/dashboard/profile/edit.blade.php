@extends('layouts.dashboard')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-2xl">
    <h2 class="text-xl font-bold text-text-main mb-6">My Profile</h2>

    <form method="POST" action="{{ route('owner.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        {{-- Avatar --}}
        <div class="bg-white rounded-2xl border border-border-light p-6">
            <h3 class="text-sm font-bold text-text-main mb-4">Profile Photo</h3>
            <div class="flex items-center gap-6">
                <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-border-light">
                <div>
                    <input type="file" name="avatar" accept="image/*" class="text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                    <p class="text-xs text-text-secondary mt-1">Max 2MB, JPEG or PNG</p>
                </div>
            </div>
            @error('avatar') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl border border-border-light p-6">
            <h3 class="text-sm font-bold text-text-main mb-4">Basic Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-text-main mb-1">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-main mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="+91 9876543210">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-main mb-1">Bio</label>
                    <textarea name="bio" rows="3" class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none" placeholder="Tell visitors about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Social Links --}}
        <div class="bg-white rounded-2xl border border-border-light p-6">
            <h3 class="text-sm font-bold text-text-main mb-4">Social Links</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-text-main mb-1">
                        <span class="material-symbols-outlined text-[14px] align-middle text-pink-500">photo_camera</span> Instagram
                    </label>
                    <input type="text" name="instagram" value="{{ old('instagram', $user->instagram) }}" class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="@yourusername">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-main mb-1">
                        <span class="material-symbols-outlined text-[14px] align-middle text-blue-600">public</span> Facebook
                    </label>
                    <input type="text" name="facebook" value="{{ old('facebook', $user->facebook) }}" class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="https://facebook.com/yourpage">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-main mb-1">
                        <span class="material-symbols-outlined text-[14px] align-middle text-slate-600">language</span> Website
                    </label>
                    <input type="url" name="user_website" value="{{ old('user_website', $user->user_website) }}" class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="https://yourwebsite.com">
                    @error('user_website') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-primary-dark transition-colors">Save Profile</button>
    </form>
</div>
@endsection
