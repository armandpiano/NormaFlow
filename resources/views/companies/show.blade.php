@extends('layouts.app')

@section('title', $company->name->toString())

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('companies.index') }}" class="text-indigo-600 hover:text-indigo-900 mb-4 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a empresas
            </a>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-16 w-16 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl font-bold text-indigo-600">{{ substr($company->name->toString(), 0, 2) }}</span>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $company->name->toString() }}</h1>
                        <p class="text-gray-600">RFC: {{ $company->rfc->toString() }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('companies.edit', $company->id->toString()) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Editar
                    </a>
                    @if($company->status === 'active')
                        <form action="{{ route('companies.suspend', $company->id->toString()) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Suspender
                            </button>
                        </form>
                    @else
                        <form action="{{ route('companies.activate', $company->id->toString()) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Activar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-8">
            @if($company->status === 'active')
                <span class="px-4 py-2 text-sm font-medium text-green-800 bg-green-100 rounded-full">Empresa Activa</span>
            @elseif($company->status === 'suspended')
                <span class="px-4 py-2 text-sm font-medium text-red-800 bg-red-100 rounded-full">Empresa Suspendida</span>
            @endif
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Contact Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Contacto</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-500">Email</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $company->email?->toString() ?? 'No registrado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Teléfono</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $company->phone?->toString() ?? 'No registrado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Sitio Web</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $company->website?->toString() ?? 'No registrado' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Location -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubicación</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-500">Dirección</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $company->address?->toString() ?? 'No registrada' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Ciudad/Estado</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            {{ $company->city?->toString() ?? '' }}{{ $company->city && $company->state ? ', ' : '' }}{{ $company->state?->toString() ?? '' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">País</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $company->country?->toString() ?? 'México' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Subscription -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Suscripción</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-500">Plan</dt>
                        <dd class="text-sm font-medium text-gray-900 capitalize">{{ $company->subscriptionPlan?->toString() ?? 'basic' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Usuarios máximos</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $company->maxUsers?->toString() ?? '10' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Sitios máximos</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $company->maxSites?->toString() ?? '3' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Sites -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Sucursales/Sitios</h2>
            </div>
            <div class="p-6">
                @if($sites->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($sites as $site)
                        <li class="py-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $site->name->toString() }}</p>
                                <p class="text-sm text-gray-500">Código: {{ $site->code->toString() }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium {{ $site->status === 'active' ? 'text-green-800 bg-green-100' : 'text-gray-800 bg-gray-100' }} rounded-full">
                                {{ $site->status }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 text-center py-4">No hay sitios registrados</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
