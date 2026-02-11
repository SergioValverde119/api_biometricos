<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { buscar, exportar } from '@/actions/App/Http/Controllers/AttendanceReportController';

const props = defineProps<{
    checadas: any[];
    filtros?: {
        codigo_empleado: string;
        fecha_inicio: string;
        fecha_fin: string;
    };
}>();

const form = useForm({
    codigo_empleado: props.filtros?.codigo_empleado || '',
    fecha_inicio: props.filtros?.fecha_inicio || '',
    fecha_fin: props.filtros?.fecha_fin || '',
});

const mostrarModalHorario = ref(false);
const horarioSeleccionado = ref<any>(null);

// Función para formatear el texto de los días según tu requerimiento
const formatearDiasTexto = (diasRaw: string) => {
    if (!diasRaw) return 'No especificado';
    const dias = diasRaw.toLowerCase();
    
    // Si contiene viernes, asumimos la jornada de oficina estándar
    if (dias.includes('viernes')) {
        return 'Lunes a Viernes';
    }
    // Si contiene sábado, asumimos el fin de semana
    if (dias.includes('sábado') || dias.includes('sabado') || dias.includes('domingo')) {
        return 'Sábado y Domingo';
    }
    
    return diasRaw;
};

const verDetalleHorario = (horario: any) => {
    horarioSeleccionado.value = horario;
    mostrarModalHorario.value = true;
};

const cerrarModal = () => {
    mostrarModalHorario.value = false;
    horarioSeleccionado.value = null;
};

const formatearFecha = (fechaRaw: string) => {
    if (!fechaRaw) return '---';
    const objetoFecha = new Date(fechaRaw);
    return objetoFecha.toLocaleString('es-MX', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
    });
};

const consultar = () => {
    form.get(buscar().url, { preserveScroll: true, preserveState: true });
};

const descargarExcel = () => {
    const params = new URLSearchParams({
        user_id: form.codigo_empleado, 
        fecha_inicio: form.fecha_inicio,
        fecha_fin: form.fecha_fin
    });
    window.location.href = `${exportar().url}?${params.toString()}`;
};
</script>

<template>
    <Head title="Monitor de Asistencia" />

    <div class="min-h-screen bg-slate-50 p-4 flex flex-col items-center font-sans text-slate-900">
        <div class="w-full max-w-[1200px]">
            <Card class="mb-6 border-none shadow-md bg-white">
                <CardHeader class="border-b border-slate-100 py-4">
                    <CardTitle class="text-xs uppercase tracking-widest text-slate-500 font-bold">Filtros de Búsqueda</CardTitle>
                </CardHeader>
                <CardContent class="pt-6 pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="grid gap-2">
                            <Label for="emp_id" class="font-bold text-xs uppercase text-slate-600">Empleado (Nombre o ID)</Label>
                            <Input id="emp_id" v-model="form.codigo_empleado" placeholder="Ej. Sergio o 1" class="h-10 border-slate-300" @keyup.enter="consultar" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="f_ini" class="font-bold text-xs uppercase text-slate-600">Desde</Label>
                            <Input id="f_ini" type="date" v-model="form.fecha_inicio" class="h-10 border-slate-300" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="f_fin" class="font-bold text-xs uppercase text-slate-600">Hasta</Label>
                            <Input id="f_fin" type="date" v-model="form.fecha_fin" class="h-10 border-slate-300" />
                        </div>
                        <div class="flex gap-2">
                            <Button @click="consultar" class="flex-1 h-10 font-bold bg-blue-600 text-white" :disabled="form.processing">Consultar</Button>
                            <Button @click="descargarExcel" variant="outline" class="h-10 px-4 font-bold border-slate-300 text-slate-700 hover:bg-slate-50" :disabled="form.processing">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-600 mr-2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Excel
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <div v-if="checadas.length > 0" class="bg-white border border-slate-200 rounded-lg shadow-lg overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-100 px-2">
                    <table class="w-full text-left text-xs font-bold uppercase text-slate-500">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 w-1/4">Fecha y Hora</th>
                                <th class="px-4 py-3 w-1/4 text-center">Empleado</th>
                                <th class="px-4 py-3 w-1/4 text-center">Horario</th>
                                <th class="px-4 py-3 w-1/4 text-right">Dispositivo</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="max-h-[600px] overflow-y-auto scrollbar-thin px-2">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="c in checadas" :key="c.id" class="hover:bg-blue-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-sm text-slate-700 w-1/4">{{ formatearFecha(c.fecha_hora) }}</td>
                                <td class="px-4 py-3 text-center w-1/4">
                                    <div v-if="c.empleado">
                                        <div class="font-bold text-slate-800 text-sm">{{ c.empleado.name }} {{ c.empleado.last_name }}</div>
                                        <div class="text-[10px] text-slate-400">ID: {{ c.user_id }}</div>
                                    </div>
                                    <span v-else class="text-xs text-slate-400">Sin Datos ({{ c.user_id }})</span>
                                </td>
                                <td class="px-4 py-3 text-center w-1/4">
                                    <button v-if="c.empleado?.schedule" @click="verDetalleHorario(c.empleado.schedule)" class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-all">
                                        {{ c.empleado.schedule.name }}
                                    </button>
                                    <span v-else class="text-xs text-slate-400">--</span>
                                </td>
                                <td class="px-4 py-3 text-right w-1/4">
                                    <span v-if="c.dispositivo" class="text-sm font-bold">{{ c.dispositivo.nombre }}</span>
                                    <span v-else class="text-xs text-amber-600 italic">{{ c.sn }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="bg-slate-50 px-4 py-2 border-t border-slate-200 flex justify-end text-xs font-bold text-slate-500 uppercase tracking-widest">
                    <span>Registros encontrados: {{ checadas.length }}</span>
                </div>
            </div>
            
            <!-- Mensaje de no resultados corregido -->
            <div v-else-if="filtros" class="text-center py-20 bg-white border border-dashed border-slate-300 rounded-lg">
                <p class="text-slate-400 font-medium">No se encontraron marcaciones para esta búsqueda.</p>
            </div>

            <!-- MODAL HORARIO -->
            <div v-if="mostrarModalHorario" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" @click.self="cerrarModal">
                <Card class="w-full max-w-md shadow-2xl animate-in zoom-in-95">
                    <CardHeader class="bg-blue-600 text-white rounded-t-lg py-3 px-4 flex flex-row justify-between items-center">
                        <CardTitle class="text-sm font-bold uppercase tracking-widest">Horario: {{ horarioSeleccionado?.name }}</CardTitle>
                        <button @click="cerrarModal" class="text-white hover:text-blue-200">✕</button>
                    </CardHeader>
                    <CardContent class="p-0">
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="p-3 bg-slate-50 font-bold text-xs uppercase text-slate-500">Días Aplicables</td>
                                    <td class="p-3 text-slate-700 font-medium">
                                        {{ formatearDiasTexto(horarioSeleccionado?.day_of_week) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-3 bg-slate-50 font-bold text-xs uppercase text-slate-500">Entrada</td>
                                    <td class="p-3 text-green-600 font-bold text-lg">{{ horarioSeleccionado?.entry_time }}</td>
                                </tr>
                                <tr>
                                    <td class="p-3 bg-slate-50 font-bold text-xs uppercase text-slate-500">Salida</td>
                                    <td class="p-3 text-red-600 font-bold text-lg">{{ horarioSeleccionado?.exit_time }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="p-3 bg-slate-50 text-right">
                            <Button @click="cerrarModal" class="bg-slate-800 text-white font-bold">Cerrar</Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>

<style scoped>
.scrollbar-thin::-webkit-scrollbar { width: 8px; }
.scrollbar-thin::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>