<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Attendance as AttendanceAction } from "@/actions/App/Http/Controllers/DeviceController";

const props = defineProps({
    attendances: Object // Recibe el objeto paginado de Laravel
});

// Formateador de fecha y hora
const formatDateTime = (date) => {
    return new Date(date).toLocaleString('es-MX', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Reporte de Asistencia" />

    <div class="py-12 px-6 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Registros de Asistencia</h1>
            
            <Link 
                :href="AttendanceAction().url" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm transition"
            >
                Refrescar Datos
            </Link>
        </div>

        <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Empleado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID Equipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Fecha y Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="record in attendances.data" :key="record.id" class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-3">
                                    {{ record.employee_id }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ record.employee ? record.employee.name : 'ID: ' + record.employee_id }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">
                            {{ record.sn }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ formatDateTime(record.timestamp) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-md bg-blue-50 text-blue-700 border border-blue-100 uppercase font-bold">
                                {{ record.status1 == 0 ? 'Entrada' : 'Salida' }}
                            </span>
                        </td>
                    </tr>
                    <tr v-if="attendances.data.length === 0">
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                            Aún no se han recibido registros del biométrico.
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-100">
                <div class="text-sm text-gray-600">
                    Mostrando {{ attendances.from }} al {{ attendances.to }} de {{ attendances.total }} registros
                </div>
                <div class="flex space-x-2">
                    <Link 
                        v-for="link in attendances.links" 
                        :key="link.label"
                        :href="link.url || '#'"
                        v-html="link.label"
                        :class="[
                            'px-3 py-1 text-sm rounded-md border transition',
                            link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50',
                            !link.url ? 'opacity-50 cursor-not-allowed' : ''
                        ]"
                    />
                </div>
            </div>
        </div>
    </div>
</template>