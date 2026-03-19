@extends('layouts.app')

@section('title', 'Nueva Auditoría')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('audits.index') }}" class="text-indigo-600 hover:text-indigo-900 mb-4 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Volver a auditorías
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Nueva Auditoría</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('audits.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Título de la Auditoría *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Auditoría NOM-035 Q4 2024">
                </div>
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                        <select id="type" name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="internal" {{ old('type') === 'internal' ? 'selected' : '' }}>Interna</option>
                            <option value="external" {{ old('type') === 'external' ? 'selected' : '' }}>Externa</option>
                            <option value="follow-up" {{ old('type') === 'follow-up' ? 'selected' : '' }}>Seguimiento</option>
                        </select>
                    </div>
                    <div>
                        <label for="scope" class="block text-sm font-medium text-gray-700 mb-2">Alcance</label>
                        <input type="text" id="scope" name="scope" value="{{ old('scope') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Todas las sedes">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio *</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Fin</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-6 flex justify-end space-x-4">
                    <a href="{{ route('audits.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancelar</a>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Crear Auditoría</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
