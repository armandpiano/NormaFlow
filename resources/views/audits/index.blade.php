@extends('layouts.app')

@section('title', 'Auditorías')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Auditorías</h1>
                <p class="mt-2 text-gray-600">Gestiona las auditorías internas y externas</p>
            </div>
            <a href="{{ route('audits.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nueva Auditoría
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <form action="{{ route('audits.index') }}" method="GET" class="flex flex-wrap gap-4">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos los estados</option>
                    <option value="planned" {{ ($filters['status'] ?? '') === 'planned' ? 'selected' : '' }}>Planificada</option>
                    <option value="in_progress" {{ ($filters['status'] ?? '') === 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                    <option value="completed" {{ ($filters['status'] ?? '') === 'completed' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelled" {{ ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                </select>
                <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos los tipos</option>
                    <option value="internal" {{ ($filters['type'] ?? '') === 'internal' ? 'selected' : '' }}>Interna</option>
                    <option value="external" {{ ($filters['type'] ?? '') === 'external' ? 'selected' : '' }}>Externa</option>
                    <option value="follow-up" {{ ($filters['type'] ?? '') === 'follow-up' ? 'selected' : '' }}>Seguimiento</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Filtrar
                </button>
            </form>
        </div>

        <!-- Audits List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auditoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fechas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($audits as $audit)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $audit->title->toString() }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($audit->description?->toString() ?? '', 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full capitalize">
                                {{ str_replace('_', ' ', $audit->type->value ?? '') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $audit->startDate->format('d/m/Y') }}</div>
                            @if($audit->endDate)
                                <div class="text-sm text-gray-500">al {{ $audit->endDate->format('d/m/Y') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($audit->status->value)
                                @case('planned')
                                    <span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">Planificada</span>
                                    @break
                                @case('in_progress')
                                    <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">En Progreso</span>
                                    @break
                                @case('completed')
                                    <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Completada</span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">Cancelada</span>
                                    @break
                                @default
                                    <span class="px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">{{ $audit->status->value }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('audits.show', $audit->id->toString()) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                            @if($audit->status->value === 'planned')
                                <form action="{{ route('audits.start', $audit->id->toString()) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">Iniciar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay auditorías</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza creando tu primera auditoría.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
