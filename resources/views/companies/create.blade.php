@extends('layouts.app')

@section('title', 'Nueva Empresa')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('companies.index') }}" class="text-indigo-600 hover:text-indigo-900 mb-4 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a empresas
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Nueva Empresa</h1>
            <p class="text-gray-600 mt-2">Registra una nueva empresa en el sistema</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('companies.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Empresa *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="rfc" class="block text-sm font-medium text-gray-700 mb-2">RFC *</label>
                        <input type="text" id="rfc" name="rfc" value="{{ old('rfc') }}" required maxlength="20" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="industry" class="block text-sm font-medium text-gray-700 mb-2">Industria</label>
                        <select id="industry" name="industry" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccionar...</option>
                            <option value="manufacturing" {{ old('industry') === 'manufacturing' ? 'selected' : '' }}>Manufactura</option>
                            <option value="services" {{ old('industry') === 'services' ? 'selected' : '' }}>Servicios</option>
                            <option value="retail" {{ old('industry') === 'retail' ? 'selected' : '' }}>Comercio</option>
                            <option value="technology" {{ old('industry') === 'technology' ? 'selected' : '' }}>Tecnología</option>
                            <option value="healthcare" {{ old('industry') === 'healthcare' ? 'selected' : '' }}>Salud</option>
                            <option value="education" {{ old('industry') === 'education' ? 'selected' : '' }}>Educación</option>
                            <option value="finance" {{ old('industry') === 'finance' ? 'selected' : '' }}>Finanzas</option>
                            <option value="other" {{ old('industry') === 'other' ? 'selected' : '' }}>Otra</option>
                        </select>
                    </div>
                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700 mb-2">Tamaño</label>
                        <select id="size" name="size" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccionar...</option>
                            <option value="micro" {{ old('size') === 'micro' ? 'selected' : '' }}>Micro (1-10 empleados)</option>
                            <option value="small" {{ old('size') === 'small' ? 'selected' : '' }}>Pequeña (11-50 empleados)</option>
                            <option value="medium" {{ old('size') === 'medium' ? 'selected' : '' }}>Mediana (51-250 empleados)</option>
                            <option value="large" {{ old('size') === 'large' ? 'selected' : '' }}>Grande (251+ empleados)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ciudad</label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <input type="text" id="state" name="state" value="{{ old('state') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Código Postal</label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" maxlength="10" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Sitio Web</label>
                    <input type="url" id="website" name="website" value="{{ old('website') }}" placeholder="https://" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="border-t border-gray-200 pt-6 flex justify-end space-x-4">
                    <a href="{{ route('companies.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Crear Empresa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
