@extends('layouts.app')

@section('title', 'Subir Evidencia')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('evidence.index') }}" class="text-indigo-600 hover:text-indigo-900 mb-4 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Volver a evidencias
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Subir Evidencia</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('evidence.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label for="requirement_id" class="block text-sm font-medium text-gray-700 mb-2">Requisito *</label>
                    <select id="requirement_id" name="requirement_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Seleccionar requisito...</option>
                        @if(isset($requirementId))
                            <option value="{{ $requirementId }}" selected>Requisito seleccionado</option>
                        @endif
                    </select>
                </div>
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Capacitación personal Q4 2024">
                </div>
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Archivo</label>
                        <select id="type" name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="document" {{ old('type') === 'document' ? 'selected' : '' }}>Documento</option>
                            <option value="photo" {{ old('type') === 'photo' ? 'selected' : '' }}>Fotografía</option>
                            <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Video</option>
                            <option value="audio" {{ old('type') === 'audio' ? 'selected' : '' }}>Audio</option>
                            <option value="certificate" {{ old('type') === 'certificate' ? 'selected' : '' }}>Certificado</option>
                            <option value="report" {{ old('type') === 'report' ? 'selected' : '' }}>Reporte</option>
                            <option value="record" {{ old('type') === 'record' ? 'selected' : '' }}>Registro</option>
                            <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    <div>
                        <label for="expiration_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Vencimiento</label>
                        <input type="date" id="expiration_date" name="expiration_date" value="{{ old('expiration_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="mb-6">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Archivo *</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                    <span>Subir archivo</span>
                                    <input id="file" name="file" type="file" required class="sr-only" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.gif,.mp4,.mp3,.wav">
                                </label>
                                <p class="pl-1">o arrastrar y soltar</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, XLS, PNG, JPG hasta 50MB</p>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-6 flex justify-end space-x-4">
                    <a href="{{ route('evidence.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancelar</a>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Subir Evidencia</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
