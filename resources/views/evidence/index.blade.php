@extends('layouts.app')

@section('title', 'Evidencias')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Evidencias</h1>
                <p class="mt-2 text-gray-600">Gestiona las evidencias de cumplimiento normativo</p>
            </div>
            <a href="{{ route('evidence.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Subir Evidencia
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <form action="{{ route('evidence.index') }}" method="GET" class="flex flex-wrap gap-4">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="approved" {{ ($filters['status'] ?? '') === 'approved' ? 'selected' : '' }}>Aprobada</option>
                    <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Rechazada</option>
                </select>
                <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos los tipos</option>
                    <option value="document" {{ ($filters['type'] ?? '') === 'document' ? 'selected' : '' }}>Documento</option>
                    <option value="photo" {{ ($filters['type'] ?? '') === 'photo' ? 'selected' : '' }}>Fotografía</option>
                    <option value="video" {{ ($filters['type'] ?? '') === 'video' ? 'selected' : '' }}>Video</option>
                    <option value="certificate" {{ ($filters['type'] ?? '') === 'certificate' ? 'selected' : '' }}>Certificado</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Filtrar
                </button>
            </form>
        </div>

        <!-- Evidence Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($evidence as $item)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <!-- Preview -->
                <div class="h-40 bg-gray-100 flex items-center justify-center">
                    @switch($item->type->value)
                        @case('photo')
                            <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            @break
                        @case('video')
                            <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            @break
                        @default
                            <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                    @endswitch
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        @switch($item->verificationStatus->value)
                            @case('pending')
                                <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">Pendiente</span>
                                @break
                            @case('approved')
                                <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Aprobada</span>
                                @break
                            @case('rejected')
                                <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">Rechazada</span>
                                @break
                            @case('revision')
                                <span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">En Revisión</span>
                                @break
                        @endswitch
                        <span class="text-xs text-gray-500">{{ $item->type->value }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 mb-1 line-clamp-2">{{ $item->title->toString() }}</h3>
                    <p class="text-xs text-gray-500 mb-3">{{ $item->createdAt->format('d/m/Y H:i') }}</p>
                    <a href="{{ route('evidence.show', $item->id->toString()) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                        Ver detalles →
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay evidencias</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza subiendo tu primera evidencia.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
