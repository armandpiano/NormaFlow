@extends('layouts.guest')

@section('title', 'NormaFlow - Gestión de Cumplimiento Normativo')

@section('content')
<div class="bg-white rounded-xl shadow-xl overflow-hidden">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-16 text-white">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                Gestión de Cumplimiento Normativo Simplificada
            </h1>
            <p class="text-xl text-indigo-100 mb-8">
                La plataforma SaaS que ayuda a empresas mexicanas a cumplir con NOM y STPS de manera eficiente
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-indigo-50 transition-colors">
                    Comenzar Gratis
                </a>
                <a href="#features" class="px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white/10 transition-colors">
                    Conocer Más
                </a>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div id="features" class="px-8 py-16">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">
            Todo lo que necesitas para cumplir
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Cumplimiento NOM/STPS</h3>
                <p class="text-gray-600">Gestiona todas tus normatividades en un solo lugar con seguimiento de vencimientos</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Evidencias Documentales</h3>
                <p class="text-gray-600">Sube y organiza documentos de cumplimiento con verificación integrada</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Auditorías Integradas</h3>
                <p class="text-gray-600">Planifica y ejecuta auditorías internas con seguimiento de hallazgos</p>
            </div>
        </div>
    </div>

    <!-- Pricing Preview -->
    <div class="bg-gray-50 px-8 py-16">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-4">
            Planes para cada tamaño de empresa
        </h2>
        <p class="text-gray-600 text-center mb-12 max-w-2xl mx-auto">
            Desde startups hasta corporaciones, tenemos un plan que se adapta a tus necesidades
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Basic -->
            <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Básico</h3>
                <p class="text-gray-600 text-sm mb-6">Para empresas que inician</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900">$999</span>
                    <span class="text-gray-600">/mes</span>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Hasta 10 usuarios
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        3 sedes
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        NOM-035 incluida
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50">
                    Comenzar
                </a>
            </div>
            <!-- Professional -->
            <div class="bg-indigo-600 rounded-xl shadow-lg p-8 relative">
                <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-lg">
                    POPULAR
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Profesional</h3>
                <p class="text-indigo-200 text-sm mb-6">Para empresas en crecimiento</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-white">$2,499</span>
                    <span class="text-indigo-200">/mes</span>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-sm text-white">
                        <svg class="w-5 h-5 text-indigo-200 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Hasta 50 usuarios
                    </li>
                    <li class="flex items-center text-sm text-white">
<svg class="w-5 h-5 text-indigo-200 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        10 sedes
                    </li>
                    <li class="flex items-center text-sm text-white">
                        <svg class="w-5 h-5 text-indigo-200 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Todas las NOM/STPS
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 bg-white text-indigo-600 rounded-lg hover:bg-indigo-50">
                    Comenzar
                </a>
            </div>
            <!-- Enterprise -->
            <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Corporativo</h3>
                <p class="text-gray-600 text-sm mb-6">Para grandes organizaciones</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900">$6,999</span>
                    <span class="text-gray-600">/mes</span>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Usuarios ilimitados
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Sedes ilimitadas
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Soporte dedicado
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50">
                    Contactar
                </a>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="px-8 py-16 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">
            ¿Listo para cumplir con confianza?
        </h2>
        <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
            Únete a las empresas mexicanas que ya confían en NormaFlow para su gestión de cumplimiento normativo
        </p>
        <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
            Crear Cuenta Gratis
        </a>
    </div>
</div>
@endsection
