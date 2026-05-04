<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { AppUser, Project } from '@/types/taskovex';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    project: Project;
    users: AppUser[];
    statuses: string[];
    priorities: string[];
}>();

const form = useForm({
    title: '',
    description: '',
    status: 'todo',
    priority: 'medium',
    due_date: '',
    assigned_user_id: '',
});

const submit = () => {
    form.post(route('projects.tasks.store', props.project.id));
};
</script>

<template>
    <Head title="Create Task" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Create task</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ project.name }}
                </p>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <form
                    class="rounded-lg border border-gray-200 bg-white p-6"
                    @submit.prevent="submit"
                >
                    <div class="space-y-5">
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Title
                            </label>
                            <input
                                v-model="form.title"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                                {{ form.errors.title }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <textarea
                                v-model="form.description"
                                rows="5"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700">
                                    Status
                                </label>
                                <select
                                    v-model="form.status"
                                    class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option v-for="status in statuses" :key="status" :value="status">
                                        {{ status.replace('_', ' ') }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">
                                    Priority
                                </label>
                                <select
                                    v-model="form.priority"
                                    class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option v-for="priority in priorities" :key="priority" :value="priority">
                                        {{ priority }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700">
                                    Due date
                                </label>
                                <input
                                    v-model="form.due_date"
                                    type="date"
                                    class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">
                                    Assigned user
                                </label>
                                <select
                                    v-model="form.assigned_user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">Unassigned</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">
                                        {{ user.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <button
                            type="submit"
                            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Create task
                        </button>
                        <Link
                            :href="route('projects.show', project.id)"
                            class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
