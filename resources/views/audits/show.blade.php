@extends('layouts.app')

@section('title', $audit->title->toString())

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('audits.index') }}" class="text-indigo-600 hover:text-indigo-900 mb-4 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a auditorías
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="px-3 py-1 text-sm font-medium text-gray-800 bg-gray-100 rounded-full capitalize">
                            {{ str_replace('_', ' ', $audit->type->value ?? '') }}
                        </span>
                        @switch($audit->status->value)
                            @case('planned')
                                <span class="px-3 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">Planificada</span>
                                @break
                            @case('in_progress')
                                <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">En Progreso</span>
                                @break
                            @case('completed')
                                <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">Completada</span>
                                @break
                            @case('cancelled')
                                <span class="px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full">Cancelada</span>
                                @break
                        @endswitch
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $audit->title->toString() }}</h1>
                </div>
                <div class="flex items-center space-x-3">
                    @if($audit->status->value === 'planned')
                        <form action="{{ route('audits.start', $audit->id->toString()) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Iniciar Auditoría
                            </button>
                        </form>
                    @elseif($audit->status->value === 'in_progress')
                        <form action="{{ route('audits.complete', $audit->id->toString()) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                Completar Auditoría
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('audits.edit', $audit->id->toString()) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Editar
                    </a>
                </div>
            </div>
            @if($audit->description)
                <p class="mt-4 text-gray-600">{{ $audit->description->toString() }}</p>
            @endif
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Fecha de Inicio</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $audit->startDate->format('d/m/Y') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Fecha de Fin</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $audit->endDate?->format('d/m/Y') ?? 'En curso' }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Hallazgos</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $stats['findings_total'] }} ({{ $stats['findings_open'] }} abiertos)</p>
            </div>
        </div>

        <!-- Findings -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Hallazgos</h2>
                <a href="{{ route('audits.add-finding', $audit->id->toString()) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                    Agregar Hallazgo
                </a>
            </div>
            <div class="p-6">
                @php
                    $findings = $audit->getFindings();
                @endphp
                @if(count($findings) > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($findings as $finding)
                        <li class="py-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-gray-900">{{ $finding->title->toString() }}</p>
                                        @switch($finding->severity->value)
                                            @case('critical')
                                                <span class="ml-2 px-2 py-0.5 text-xs font-medium text-red-800 bg-red-100 rounded">Crítico</span>
                                                @break
                                            @case('major')
                                                <span class="ml-2 px-2 py-0.5 text-xs font-medium text-orange-800 bg-orange-100 rounded">Mayor</span>
                                                @break
                                            @case('minor')
                                                <span class="ml-2 px-2 py-0.5 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">Menor</span>
                                                @break
                                            @case('observation')
                                                <span class="ml-2 px-2 py-0.5 text-xs font-medium text-blue-800 bg-blue-100 rounded">Observación</span>
                                                @break
                                        @endswitch
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($finding->description?->toString() ?? '', 100) }}</p>
                                </div>
                                @switch($finding->status->value)
                                    @case('open')
                                        <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">Abierto</span>
                                        @break
                                    @case('in_progress')
                                        <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">En Progreso</span>
                                        @break
                                    @case('resolved')
                                        <span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">Resuelto</span>
                                        @break
                                    @case('closed')
                                        <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Cerrado</span>
                                        @break
                                @endswitch
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 text-center py-8">No hay hallazgos registrados</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
