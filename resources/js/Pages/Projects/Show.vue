<script setup lang="ts">
import ActivityFeed from '@/Components/ActivityFeed.vue';
import KanbanBoard from '@/Components/KanbanBoard.vue';
import TaskModal from '@/Components/TaskModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { ActivityLogItem, AppUser, Project, TaskovexTask } from '@/types/taskovex';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    project: Project;
    users: AppUser[];
    statuses: string[];
    priorities: string[];
    activityLogs: ActivityLogItem[];
}>();

const selectedTask = ref<TaskovexTask | null>(null);
const tasks = computed(() => props.project.tasks ?? []);

const form = useForm({
    name: props.project.name,
    description: props.project.description ?? '',
    color: props.project.color,
});

const submit = () => {
    form.put(route('projects.update', props.project.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="project.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-3">
                        <span
                            class="h-3 w-3 rounded-full"
                            :style="{ backgroundColor: project.color }"
                        />
                        <h1 class="text-xl font-semibold text-gray-900">
                            {{ project.name }}
                        </h1>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ project.description || 'No description' }}
                    </p>
                </div>
                <Link
                    :href="route('projects.tasks.create', project.id)"
                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    New task
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-[1fr_340px]">
                    <section>
                        <KanbanBoard
                            :tasks="tasks"
                            :statuses="statuses"
                            @open="selectedTask = $event"
                        />
                    </section>

                    <aside class="space-y-6">
                        <form
                            class="rounded-lg border border-gray-200 bg-white p-5"
                            @submit.prevent="submit"
                        >
                            <h2 class="text-base font-semibold text-gray-950">
                                Project details
                            </h2>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">
                                        Name
                                    </label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
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
                                    class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-800 disabled:opacity-50"
                                    :disabled="form.processing"
                                >
                                    Save changes
                                </button>
                            </div>
                        </form>

                        <div class="rounded-lg border border-gray-200 bg-white p-5">
                            <h2 class="text-base font-semibold text-gray-950">
                                Team
                            </h2>
                            <div class="mt-4 space-y-3">
                                <div
                                    v-for="user in users"
                                    :key="user.id"
                                    class="flex items-center justify-between gap-3 text-sm"
                                >
                                    <span class="font-medium text-gray-800">
                                        {{ user.name }}
                                    </span>
                                    <span class="truncate text-gray-500">
                                        {{ user.email }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>

                <section>
                    <h2 class="mb-4 text-lg font-semibold text-gray-950">
                        Activity
                    </h2>
                    <ActivityFeed :items="activityLogs" />
                </section>
            </div>
        </div>

        <TaskModal
            :show="Boolean(selectedTask)"
            :task="selectedTask"
            @close="selectedTask = null"
        />
    </AuthenticatedLayout>
</template>
