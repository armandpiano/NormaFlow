@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Mi Perfil</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="text-center">
                        <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl font-bold text-indigo-600">{{ substr(Auth::user()->name, 0, 2) }}</span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-500">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-2 px-3 py-1 text-xs font-medium text-indigo-800 bg-indigo-100 rounded-full capitalize">
                            {{ Auth::user()->role->value }}
                        </span>
                    </div>
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <nav class="space-y-2">
                            <a href="#personal" class="flex items-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Información Personal
                            </a>
                            <a href="#security" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                Seguridad
                            </a>
                            <a href="#notifications" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                Notificaciones
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Personal Information -->
                <div id="personal" class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Información Personal</h3>
                    <form action="#" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                                <input type="text" value="{{ Auth::user()->name }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                                <input type="email" value="{{ Auth::user()->email }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                <input type="text" value="{{ Auth::user()->phone?->toString() ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                                <input type="text" value="{{ Auth::user()->department?->toString() ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Guardar Cambios</button>
                        </div>
                    </form>
                </div>

                <!-- Security -->
                <div id="security" class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Seguridad</h3>
                    <form action="#" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña Actual</label>
                                <input type="password" name="current_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                                <input type="password" name="new_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                                <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Actualizar Contraseña</button>
                        </div>
                    </form>
                </div>

                <!-- Notifications -->
                <div id="notifications" class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Preferencias de Notificaciones</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <div>
                                <p class="font-medium text-gray-900">Recordatorios de Vencimiento</p>
                                <p class="text-sm text-gray-500">Recibe alertas cuando las evidencias estén por vencer</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <div>
                                <p class="font-medium text-gray-900">Nuevos Hallazgos</p>
                                <p class="text-sm text-gray-500">Recibe notificación cuando se registre un nuevo hallazgo</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="font-medium text-gray-900">Resumen Semanal</p>
                                <p class="text-sm text-gray-500">Recibe un resumen de actividad cada semana</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
