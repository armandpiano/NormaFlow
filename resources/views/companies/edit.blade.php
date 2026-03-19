@extends('layouts.app')

@section('title', 'Editar Empresa')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('companies.show', $company->id->toString()) }}" class="text-indigo-600 hover:text-indigo-900 mb-4 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a la empresa
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Editar Empresa</h1>
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

            <form action="{{ route('companies.update', $company->id->toString()) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Empresa *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $company->name->toString()) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="rfc" class="block text-sm font-medium text-gray-700 mb-2">RFC</label>
                        <input type="text" value="{{ $company->rfc->toString() }}" disabled class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
                        <p class="text-xs text-gray-500 mt-1">El RFC no puede ser modificado</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="industry" class="block text-sm font-medium text-gray-700 mb-2">Industria</label>
                        <select id="industry" name="industry" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccionar...</option>
                            <option value="manufacturing" {{ $company->industry?->toString() === 'manufacturing' ? 'selected' : '' }}>Manufactura</option>
                            <option value="services" {{ $company->industry?->toString() === 'services' ? 'selected' : '' }}>Servicios</option>
                            <option value="retail" {{ $company->industry?->toString() === 'retail' ? 'selected' : '' }}>Comercio</option>
                            <option value="technology" {{ $company->industry?->toString() === 'technology' ? 'selected' : '' }}>Tecnología</option>
                        </select>
                    </div>
                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700 mb-2">Tamaño</label>
                        <select id="size" name="size" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccionar...</option>
                            <option value="micro" {{ $company->size?->toString() === 'micro' ? 'selected' : '' }}>Micro</option>
                            <option value="small" {{ $company->size?->toString() === 'small' ? 'selected' : '' }}>Pequeña</option>
                            <option value="medium" {{ $company->size?->toString() === 'medium' ? 'selected' : '' }}>Mediana</option>
                            <option value="large" {{ $company->size?->toString() === 'large' ? 'selected' : '' }}>Grande</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $company->address?->toString()) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ciudad</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $company->city?->toString()) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <input type="text" id="state" name="state" value="{{ old('state', $company->state?->toString()) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Código Postal</label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $company->postalCode?->toString()) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $company->phone?->toString()) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $company->email?->toString()) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Sitio Web</label>
                    <input type="url" id="website" name="website" value="{{ old('website', $company->website?->toString()) }}" placeholder="https://" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="border-t border-gray-200 pt-6 flex justify-end space-x-4">
                    <a href="{{ route('companies.show', $company->id->toString()) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
