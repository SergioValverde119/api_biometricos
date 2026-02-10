<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
// Importamos 'buscar' y 'exportar' del controlador
import { buscar, exportar } from '@/actions/App/Http/Controllers/AttendanceReportController';
import { ArrowDownTrayIcon } from '@heroicons/vue/24/outline'; // Icono opcional si lo tienes instalado

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

// Formato de fecha legible (Día/Mes/Año Hora:Min)
const formatearFecha = (fechaRaw: string) => {
    if (!fechaRaw) return '---';
    const objetoFecha = new Date(fechaRaw);
    return objetoFecha.toLocaleString('es-MX', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    });
};

const consultar = () => {
    // Cambiamos post por get
    form.get(buscar().url, { 
        preserveScroll: true,
        preserveState: true 
    });
};

// --- NUEVA FUNCIÓN PARA EXPORTAR ---
const descargarExcel = () => {
    // Construimos la URL con los parámetros actuales del formulario
    // Usamos URLSearchParams para manejar correctamente los caracteres especiales
    const params = new URLSearchParams({
        user_id: form.codigo_empleado, // Mapeamos 'codigo_empleado' del form a 'user_id' del backend
        fecha_inicio: form.fecha_inicio,
        fecha_fin: form.fecha_fin
    });

    // Redirigimos para descargar el archivo usando la ruta generada por Wayfinder
    // La ruta 'exportar' corresponde al método exportar() en AttendanceReportController
    window.location.href = `${exportar().url}?${params.toString()}`;
};
</script>

<template>
    <Head title="Monitor de Asistencia" />

    <div class="min-h-screen bg-slate-50 p-4 flex flex-col items-center font-sans text-slate-900">
        <div class="w-full max-w-[1200px]">
            
            <Card class="mb-6 border-none shadow-md bg-white">
                <CardHeader class="border-b border-slate-100 py-4">
                    <CardTitle class="text-xs uppercase tracking-widest text-slate-500 font-bold">
                        Filtros de Búsqueda
                    </CardTitle>
                </CardHeader>
                <CardContent class="pt-6 pb-6">
                    <!-- Cambiamos @submit.prevent="consultar" para manejar botones separados -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="grid gap-2">
                            <Label for="emp_id" class="font-bold text-xs uppercase text-slate-600">ID Empleado</Label>
                            <Input 
                                id="emp_id" 
                                v-model="form.codigo_empleado" 
                                placeholder="Ej. 01010101" 
                                class="h-10 border-slate-300 focus:ring-2 focus:ring-blue-500" 
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="f_ini" class="font-bold text-xs uppercase text-slate-600">Desde</Label>
                            <Input 
                                id="f_ini" 
                                type="date" 
                                v-model="form.fecha_inicio" 
                                class="h-10 border-slate-300" 
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="f_fin" class="font-bold text-xs uppercase text-slate-600">Hasta</Label>
                            <Input 
                                id="f_fin" 
                                type="date" 
                                v-model="form.fecha_fin" 
                                class="h-10 border-slate-300" 
                            />
                        </div>
                        
                        <!-- Contenedor de Botones -->
                        <div class="flex gap-2">
                            <Button 
                                @click="consultar"
                                class="flex-1 h-10 font-bold bg-blue-600 hover:bg-blue-700 shadow-sm transition-all text-white" 
                                :disabled="form.processing"
                            >
                                {{ form.processing ? '...' : 'Consultar' }}
                            </Button>

                            <!-- BOTÓN DE EXCEL -->
                            <Button 
                                @click="descargarExcel"
                                variant="outline"
                                class="h-10 px-4 font-bold border-slate-300 text-slate-700 hover:bg-slate-50 shadow-sm"
                                :disabled="form.processing"
                                title="Descargar reporte en Excel"
                            >
                                <!-- Icono SVG manual si no tienes Heroicons instalados -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-600">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                <span class="sr-only md:not-sr-only md:ml-2">Excel</span>
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <div v-if="checadas.length > 0" class="bg-white border border-slate-200 rounded-lg shadow-lg overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-100 px-2">
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 font-bold text-xs uppercase tracking-widest w-1/3">Fecha y Hora</th>
                                <th class="px-4 py-3 font-bold text-xs uppercase tracking-widest text-center w-1/3">ID Usuario</th>
                                <th class="px-4 py-3 font-bold text-xs uppercase tracking-widest text-right w-1/3">Dispositivo</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                
                <div class="max-h-[600px] overflow-y-auto scrollbar-thin px-2">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="c in checadas" :key="c.id" class="hover:bg-blue-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-sm text-slate-700 w-1/3">
                                    {{ formatearFecha(c.fecha_hora) }}
                                </td>
                                
                                <td class="px-4 py-3 text-center w-1/3">
                                    <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full font-bold text-sm">
                                        {{ c.user_id }}
                                    </span>
                                </td>
                                
                                <td class="px-4 py-3 text-right w-1/3">
                                    <span v-if="c.dispositivo" class="text-sm font-bold text-slate-800">
                                        {{ c.dispositivo.nombre }}
                                    </span>
                                    <span v-else class="text-xs font-medium text-amber-600 italic">
                                        ID Desconocido: {{ c.sn }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="bg-slate-50 px-4 py-2 border-t border-slate-200 flex justify-end text-xs font-bold text-slate-500 uppercase tracking-widest">
                    <span>Registros encontrados: {{ checadas.length }}</span>
                </div>
            </div>

            <div v-else-if="filtros" class="text-center py-20 bg-white border border-dashed border-slate-300 rounded-lg">
                <p class="text-slate-400 font-medium">No hay marcaciones para esta búsqueda.</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Estilo para la barra de scroll interna (Chrome/Safari/Edge) */
.scrollbar-thin::-webkit-scrollbar {
    width: 8px;
}
.scrollbar-thin::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>