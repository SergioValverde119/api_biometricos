<script setup>
import { Head, Link } from '@inertiajs/vue3';
// Importamos la acción de Wayfinder para el botón de refrescar
import { Index } from "@/actions/App/Http/Controllers/DeviceController";

// Recibimos los datos que envía el controlador
const props = defineProps({
    devices: Array,
    total_online: Number
});

// Función para formatear la fecha de última conexión
const formatDate = (date) => {
    if (!date) return 'Nunca';
    return new Date(date).toLocaleString();
};
</script>

<template>
    <Head title="Dispositivos Biométricos" />

    <div class="py-12 px-6 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Gestión de Biométricos
            </h1>
            
            <Link 
                :href="Index().url" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition"
            >
                Actualizar Estado
            </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500 uppercase font-semibold">Equipos en Línea</p>
                <p class="text-3xl font-bold text-green-600">{{ total_online }}</p>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">S/N Dispositivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Última Conexión</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="device in devices" :key="device.id">
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-900">
                            {{ device.no_sn }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="device.online ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                                  class="px-2 py-1 text-xs rounded-full font-semibold">
                                {{ device.online ? 'Online' : 'Offline' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ formatDate(device.online) }}
                        </td>
                    </tr>
                    <tr v-if="devices.length === 0">
                        <td colspan="3" class="px-6 py-10 text-center text-gray-400">
                            No hay dispositivos registrados aún.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>