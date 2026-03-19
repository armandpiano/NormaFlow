@extends('layouts.app')

@section('title', 'Nueva Normatividad')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('regulations.index') }}" class="text-indigo-600 hover:text-indigo-900 mb-4 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Volver a normatividades
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Nueva Normatividad</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('regulations.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Código *</label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="NOM-035-STPS-2018">
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                        <select id="type" name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="NOM" {{ old('type') === 'NOM' ? 'selected' : '' }}>NOM</option>
                            <option value="NMX" {{ old('type') === 'NMX' ? 'selected' : '' }}>NMX</option>
                            <option value="PA" {{ old('type') === 'PA' ? 'selected' : '' }}>Proyecto de Norma</option>
                            <option value="STPS" {{ old('type') === 'STPS' ? 'selected' : '' }}>STPS</option>
                        </select>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="issuing_authority" class="block text-sm font-medium text-gray-700 mb-2">Autoridad</label>
                        <input type="text" id="issuing_authority" name="issuing_authority" value="{{ old('issuing_authority', 'STPS') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="scope" class="block text-sm font-medium text-gray-700 mb-2">Alcance</label>
                        <select id="scope" name="scope" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="Federal" {{ old('scope') === 'Federal' ? 'selected' : '' }}>Federal</option>
                            <option value="Estatal" {{ old('scope') === 'Estatal' ? 'selected' : '' }}>Estatal</option>
                            <option value="Municipal" {{ old('scope') === 'Municipal' ? 'selected' : '' }}>Municipal</option>
                        </select>
                    </div>
                    <div>
                        <label for="effective_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Vigencia</label>
                        <input type="date" id="effective_date" name="effective_date" value="{{ old('effective_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="mb-6">
                    <label for="url" class="block text-sm font-medium text-gray-700 mb-2">URL Oficial</label>
                    <input type="url" id="url" name="url" value="{{ old('url') }}" placeholder="https://" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="border-t border-gray-200 pt-6 flex justify-end space-x-4">
                    <a href="{{ route('regulations.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancelar</a>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Crear Normatividad</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
