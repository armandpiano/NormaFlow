@extends('layouts.app')

@section('title', 'Normatividades')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Normatividades</h1>
                <p class="mt-2 text-gray-600">Consulta las normas NOM y STPS disponibles</p>
            </div>
            <a href="{{ route('regulations.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Nueva Normatividad
            </a>
        </div>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Codigo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($regulations as $regulation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $regulation->code->toString() }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($regulation->name->toString(), 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded-full">{{ $regulation->type?->value ?? 'N/A' }}</span></td>
                        <td class="px-6 py-4 whitespace-nowrap">@if($regulation->isActive)<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Activa</span>@else<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Inactiva</span>@endif</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><a href="{{ route('regulations.show', $regulation->id->toString()) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No hay normatividades registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
