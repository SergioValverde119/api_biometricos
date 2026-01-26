<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Store } from "@/actions/App/Http/Controllers/EmployeeController";

const props = defineProps({
    employees: Array
});

// Formulario reactivo para vincular nombres
const form = useForm({
    employee_id: '',
    name: ''
});

const submit = () => {
    // Usamos Wayfinder para enviar los datos al controlador
    form.post(Store().url, {
        onSuccess: () => form.reset(),
    });
};

const editEmployee = (emp) => {
    form.employee_id = emp.employee_id;
    form.name = emp.name;
};
</script>

<template>
    <Head title="Vincular Empleados" />

    <div class="py-12 px-6 max-w-5xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Vinculación de IDs y Nombres</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="font-semibold text-gray-700 mb-4">Registrar / Editar</h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">ID del Biométrico</label>
                            <input v-model="form.employee_id" type="number" 
                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                placeholder="Ej: 101" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Nombre Completo</label>
                            <input v-model="form.name" type="text" 
                                class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                placeholder="Ej: Juan Pérez" required>
                        </div>
                        <button type="submit" :disabled="form.processing"
                            class="w-full bg-indigo-600 text-white py-2 rounded-lg font-bold hover:bg-indigo-700 transition disabled:opacity-50">
                            {{ form.processing ? 'Guardando...' : 'Guardar Vínculo' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nombre Asignado</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="emp in employees" :key="emp.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-mono font-bold text-indigo-600">{{ emp.employee_id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ emp.name || 'Sin nombre asignado' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="editEmployee(emp)" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>