@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Dashboard
            </h1>
            <p class="mt-2 text-gray-600">
                Bienvenido, {{ $stats['user']['name'] }}. Aquí está el resumen de tu actividad.
            </p>
        </div>

        <!-- Stats Grid -->
        @if(isset($stats['compliance']))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Requirements -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Requisitos</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['compliance']['total_requirements'] }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-lg">
                        <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Compliance Rate -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Cumplimiento</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['compliance']['coverage_percentage'] ?? 0 }}%</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Evidence -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Evidencias Pendientes</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['evidence']['pending_verification'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Open Findings -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Hallazgos Abiertos</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['findings']['by_status']['open'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Upcoming Tasks -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Próximas Tareas</h2>
                </div>
                <div class="p-6">
                    @if(count($upcomingTasks) > 0)
                        <ul class="space-y-4">
                            @foreach($upcomingTasks as $task)
                            <li class="flex items-start">
                                <div class="flex-shrink-0">
                                    @if($task['priority'] === 'high')
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-red-100 text-red-600 text-xs font-medium rounded-full">!</span>
                                    @elseif($task['priority'] === 'medium')
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-yellow-100 text-yellow-600 text-xs font-medium rounded-full">~</span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-600 text-xs font-medium rounded-full">i</span>
                                    @endif
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $task['title'] }}</p>
                                    <p class="text-sm text-gray-500">Fecha: {{ $task['due_date'] }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 text-center py-4">No hay tareas pendientes</p>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Actividad Reciente</h2>
                </div>
                <div class="p-6">
                    @if(count($recentActivity) > 0)
                        <ul class="space-y-4">
                            @foreach($recentActivity as $activity)
                            <li class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-2 h-2 bg-indigo-600 rounded-full"></div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity['message'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $activity['time'] }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 text-center py-4">No hay actividad reciente</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
