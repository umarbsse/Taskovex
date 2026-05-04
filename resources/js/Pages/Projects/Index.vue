<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { Project } from '@/types/taskovex';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    projects: Project[];
}>();

const form = useForm({
    name: '',
    description: '',
    color: '#2563eb',
});

const submit = () => {
    form.post(route('projects.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('name', 'description'),
    });
};
</script>

<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Projects</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Organize work into focused spaces.
                </p>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[360px_1fr] lg:px-8">
                <form
                    class="rounded-lg border border-gray-200 bg-white p-5"
                    @submit.prevent="submit"
                >
                    <h2 class="text-base font-semibold text-gray-950">
                        New project
                    </h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Name
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                {{ form.errors.name }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <textarea
                                v-model="form.description"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Color
                            </label>
                            <input
                                v-model="form.color"
                                type="color"
                                class="mt-1 h-10 w-16 rounded border border-gray-300 bg-white p-1"
                            />
                        </div>
                        <button
                            type="submit"
                            class="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Create project
                        </button>
                    </div>
                </form>

                <div class="grid gap-4 md:grid-cols-2">
                    <Link
                        v-for="project in projects"
                        :key="project.id"
                        :href="route('projects.show', project.id)"
                        class="rounded-lg border border-gray-200 bg-white p-5 transition hover:border-gray-300 hover:shadow-sm"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="h-3 w-3 rounded-full"
                                        :style="{ backgroundColor: project.color }"
                                    />
                                    <h2 class="truncate font-semibold text-gray-950">
                                        {{ project.name }}
                                    </h2>
                                </div>
                                <p class="mt-3 line-clamp-2 text-sm text-gray-600">
                                    {{ project.description || 'No description' }}
                                </p>
                            </div>
                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                {{ project.tasks_count }}
                            </span>
                        </div>
                        <div class="mt-5 h-2 rounded-full bg-gray-100">
                            <div
                                class="h-2 rounded-full bg-blue-600"
                                :style="{
                                    width: `${Math.round((project.completed_tasks_count / Math.max(project.tasks_count, 1)) * 100)}%`,
                                }"
                            />
                        </div>
                    </Link>

                    <div
                        v-if="!projects.length"
                        class="rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center text-sm text-gray-500 md:col-span-2"
                    >
                        Your projects will appear here.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
