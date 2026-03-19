@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Configuración del Sistema</h1>

        <div class="bg-white rounded-xl shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">General</h2>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Empresa</label>
                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Zona Horaria</label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="America/Mexico_City">Ciudad de México (UTC-6)</option>
                        <option value="America/Tijuana">Tijuana (UTC-8)</option>
                        <option value="America/Cancun">Cancún (UTC-5)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formato de Fecha</label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="d/m/Y">DD/MM/YYYY</option>
                        <option value="m/d/Y">MM/DD/YYYY</option>
                        <option value="Y-m-d">YYYY-MM-DD</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Guardar</button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Notificaciones por Correo</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <div>
                            <p class="font-medium text-gray-900">Notificaciones de Vencimiento</p>
                            <p class="text-sm text-gray-500">Días antes de que venza una evidencia</p>
                        </div>
                        <select class="px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="7">7 días</option>
                            <option value="14">14 días</option>
                            <option value="30" selected>30 días</option>
                            <option value="60">60 días</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <div>
                            <p class="font-medium text-gray-900">Email de Destino para Alertas</p>
                            <p class="text-sm text-gray-500">Recibirá las notificaciones del sistema</p>
                        </div>
                        <input type="email" value="{{ Auth::user()->email }}" class="w-64 px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Guardar</button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Usuarios y Permisos</h2>
            </div>
            <div class="p-6">
                <div class="mb-4 flex justify-between items-center">
                    <p class="text-sm text-gray-600">Gestiona los usuarios de tu empresa y sus permisos de acceso</p>
                    <a href="#" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                        Agregar Usuario
                    </a>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Usuario</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Rol</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Estado</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-xs font-medium text-indigo-600">{{ substr(Auth::user()->name, 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4"><span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded-full capitalize">{{ Auth::user()->role->value }}</span></td>
                            <td class="px-4 py-4"><span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Activo</span></td>
                            <td class="px-4 py-4 text-right"><a href="#" class="text-indigo-600 hover:text-indigo-900 text-sm">Editar</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
