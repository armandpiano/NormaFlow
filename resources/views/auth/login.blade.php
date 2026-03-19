@extends('layouts.guest')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="bg-white rounded-xl shadow-xl overflow-hidden">
    <div class="p-8">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">
            Bienvenido de nuevo
        </h2>
        <p class="text-gray-600 text-center mb-8">
            Ingresa tus credenciales para acceder al sistema
        </p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Correo electrónico
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    placeholder="correo@empresa.com" required autofocus>
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Contraseña
                </label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    placeholder="••••••••" required>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-8">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Iniciar Sesión
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="bg-gray-50 px-8 py-4 text-center">
        <p class="text-sm text-gray-600">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                Regístrate aquí
            </a>
        </p>
    </div>
</div>
@endsection
