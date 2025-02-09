{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

@extends('layouts.auth')

@section('title', 'Register')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
        
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Registerr</h4>
        </div>

        <div class="card-body">
            <form method="POST"
                action="{{route('register')}}"
                class="needs-validation"
                novalidate="">@csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email"
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email"
                        tabindex="1"
                        required
                        autofocus>
                   
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    
                </div>
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input id="name"
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        name="name"
                        tabindex="1"
                        required
                        autofocus>
                   
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    
                </div>
                <div class="form-group">
                    <label for="gen">Gen</label>
                    <select name="gen" class="form-control @error('gen') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                   
                    @error('gen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    
                </div>

                <div class="form-group">
                    <label for="departement">Departemen</label>
                    <select name="departement" class="form-control @error('departement') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="Internal">Internal</option>
                        <option value="Eksternal">Eksternal</option>
                        <option value="Medinfo">Medinfo</option>
                        <option value="Riedu">Riedu</option>
                        <option value="EnB">EnB</option>
                    </select>
                   
                    @error('departement')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    
                </div>

                <div class="form-group">
                    <label for="comdev">Community Development</label>
                    <select name="comdev" class="form-control @error('comdev') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="kriwess">kriwess</option>
                        <option value="bank sampah">bank sampah</option>
                        <option value="eco enzym">eco enzym</option>
                    </select>
                   
                    @error('comdev')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    
                </div>

                <div class="form-group">
                    <label for="angkatan">Tahun Angkatan</label>
                    <select name="angkatan" class="form-control @error('angkatan') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>
                   
                    @error('angkatan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    
                </div>

                <div class="form-group">
                    <label for="major">Jurusan</label>
                    <input id="major"
                        type="text"
                        class="form-control @error('major') is-invalid @enderror"
                        name="major"
                        tabindex="1"
                        required
                        autofocus>
                   
                    @error('major')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password"
                        type="password"
                        class="form-control  @error('password') is-invalid @enderror"
                        name="password"
                        tabindex="2"
                        required>
                     @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Passsword</label>
                    <input id="password_confirmation"
                        type="password"
                        class="form-control  @error('password_confirmation') is-invalid @enderror"
                        name="password_confirmation"
                        tabindex="2"
                        required>
                     @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                            name="remember"
                            class="custom-control-input"
                            tabindex="3"
                            id="remember-me">
                        <label class="custom-control-label"
                            for="remember-me">Remember Me</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit"
                        class="btn btn-primary btn-lg btn-block"
                        tabindex="4">
                        Login
                    </button>
                </div>
            </form>
        
        </div>
    </div>
@endsection