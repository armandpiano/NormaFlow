@extends('layouts.app')

@section('title', $regulation->code->toString())

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('regulations.index') }}" class="text-indigo-600 hover:text-indigo-900 mb-4 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Volver
        </a>
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <span class="px-3 py-1 text-sm font-medium text-indigo-800 bg-indigo-100 rounded-full mr-3">{{ $regulation->type?->value ?? 'N/A' }}</span>
                    <h1 class="text-3xl font-bold text-gray-900 inline">{{ $regulation->code->toString() }}</h1>
                </div>
                <a href="{{ route('regulations.edit', $regulation->id->toString()) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Editar</a>
            </div>
            <p class="text-xl text-gray-600 mt-4">{{ $regulation->name->toString() }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="text-sm font-medium text-gray-500">Autoridad</h3><p class="text-lg font-semibold">{{ $regulation->issuingAuthority?->toString() ?? 'STPS' }}</p></div>
            <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="text-sm font-medium text-gray-500">Alcance</h3><p class="text-lg font-semibold">{{ $regulation->scope?->toString() ?? 'Federal' }}</p></div>
            <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="text-sm font-medium text-gray-500">Requisitos</h3><p class="text-lg font-semibold">{{ $requirements->count() }}</p></div>
        </div>
        @if($regulation->description)
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Descripcion</h2>
            <p class="text-gray-600">{{ $regulation->description->toString() }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
