@extends('layouts.app')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
   
    .mr-auto {
        float: right;
    }
    .mt-5 {
        margin-top: 3em;
    }
    .mt-4 {
        margin-top:3em !important;
    }
    .mb-5 {
        margin-bottom: 3em !important;
    }
    .mt1 {
        margin: 1em 0 !important;
    }
    .mb-2 {
        margin-top: 2em !important;
    }
    .mb-10 {
        margin-top: 6em !important;
    }
    .w-50 {
        width: 50%;
    }
    .ml-auto {
        float:left;
    }
    .clear {
        clear: both !important;
    }
    .w-16 {
        width: 100px;
        height: 100px;
    }
</style>

<div class="container mx-auto">
    <div class="bg-white p-6 rounded-lg shadow-lg w-50">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div class="items-center h-full">
            <!-- Profile Image -->
            <div class="mr-4 ml-auto h-full w-50">
                <h1 class="font-rajdhani font-md font-semibold">Profilbild</h1>
                <img id="profileImagePreview" 
                     src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('user.png') }}" 
                     alt="Profile Image" 
                     width="100%"
                     class="rounded-full mt1 w-16 w-full h-full object-cover">
                <h2 class="text-lg font-bold font-playfair">{{ $user->name }}</h2>
            </div>
            
            <!-- Change Photo Button -->
            <div class="mr-auto w-50 h-full items-center">
                <button type="button" onclick="document.getElementById('profileImageInput').click()" class="border border-gray-400 px-5 mr-auto px-4 py-2 mb-2 rounded font-rajdhani font-semibold">
                    Foto ändern
                </button>
                <!-- Hidden File Input -->
                <input type="file" id="profileImageInput" name="profile_image" accept="image/*" style="display: none;" onchange="previewImage(event)">
            </div>
        </div>

        <div class="w-full clear"></div>
        <!-- User's Name -->

        <!-- Form -->
            @csrf
            @method('POST')

            <!-- First Name Field -->
            <div class="mt-4 font-rajdhani">
                <label class="block text-gray-700 font-medium mb-1 font-rajdhani font-semibold" for="first_name">Vorname</label>
                <input type="text" id="first_name" name="first_name" 
                       value="{{ old('first_name', $user->first_name) }}" 
                       class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500" required>
                @error('first_name')
                    <span class="text-red-500 text-sm font-rajdhani font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Last Name Field -->
            <div>
                <label class="block text-gray-700 font-medium mb-1 font-rajdhani font-semibold" for="last_name">Nachname</label>
                <input type="text" id="last_name" name="last_name" 
                       value="{{ old('last_name', $user->last_name) }}" 
                       class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500 font-rajdhani" required>
                @error('last_name')
                    <span class="text-red-500 text-sm font-rajdhani font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1 font-rajdhani font-semibold" for="password">Passwort</label>
                <input type="password" id="password" name="password" 
                    placeholder="********" 
                    class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500 font-rajdhani">
                @error('password')
                    <span class="text-red-500 text-sm font-rajdhani font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Confirmation Field -->
            <div>
                <label class="block text-gray-700 font-medium mb-1 font-rajdhani font-semibold" for="password_confirmation">Passwort bestätigen</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                    placeholder="********" 
                    class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500 font-rajdhani">
            </div>
            <!-- Save Changes Button -->
            <button type="submit" class="bg-black text-white w-full py-2 rounded hover:bg-gray-800 mb-5 font-rajdhani font-semibold">
                Änderungen speichern
            </button>
        </form>
    </div>
</div>
<script>
    // JavaScript function to preview the selected image
    function previewImage(event) {
        if (event.target.files.length > 0) {
            console.log("File selected:", event.target.files[0].name);
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profileImagePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        } else {
            console.log("No file selected.");
        }
    }
</script>
@endsection
