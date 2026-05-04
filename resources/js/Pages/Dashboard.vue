<script setup lang="ts">
import ActivityFeed from '@/Components/ActivityFeed.vue';
import KanbanBoard from '@/Components/KanbanBoard.vue';
import TaskModal from '@/Components/TaskModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { ActivityLogItem, Project, TaskovexTask } from '@/types/taskovex';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    stats: {
        projects: number;
        open_tasks: number;
        completed_tasks: number;
        due_soon: number;
    };
    projects: Project[];
    tasks: TaskovexTask[];
    activityLogs: ActivityLogItem[];
}>();

const statuses = ['todo', 'in_progress', 'review', 'completed'];
const selectedTask = ref<TaskovexTask | null>(null);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl font-semibold leading-tight text-gray-900">
                        Dashboard
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Work, deadlines, and project momentum in one view.
                    </p>
                </div>
                <Link
                    :href="route('projects.index')"
                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    New project
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <p class="text-sm font-medium text-gray-500">Projects</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-950">
                            {{ stats.projects }}
                        </p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <p class="text-sm font-medium text-gray-500">Open tasks</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-950">
                            {{ stats.open_tasks }}
                        </p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-950">
                            {{ stats.completed_tasks }}
                        </p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <p class="text-sm font-medium text-gray-500">Due soon</p>
                        <p class="mt-2 text-3xl font-semibold text-gray-950">
                            {{ stats.due_soon }}
                        </p>
                    </div>
                </div>

                <section>
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-950">
                            Current workflow
                        </h2>
                    </div>
                    <KanbanBoard
                        :tasks="tasks"
                        :statuses="statuses"
                        @open="selectedTask = $event"
                    />
                </section>

                <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
                    <section>
                        <h2 class="mb-4 text-lg font-semibold text-gray-950">
                            Active projects
                        </h2>
                        <div class="grid gap-4 md:grid-cols-2">
                            <Link
                                v-for="project in projects"
                                :key="project.id"
                                :href="route('projects.show', project.id)"
                                class="rounded-lg border border-gray-200 bg-white p-5 transition hover:border-gray-300 hover:shadow-sm"
                            >
                                <div class="flex items-center gap-3">
                                    <span
                                        class="h-3 w-3 rounded-full"
                                        :style="{ backgroundColor: project.color }"
                                    />
                                    <h3 class="font-semibold text-gray-950">
                                        {{ project.name }}
                                    </h3>
                                </div>
                                <p class="mt-3 line-clamp-2 text-sm text-gray-600">
                                    {{ project.description || 'No description' }}
                                </p>
                                <div class="mt-4 h-2 rounded-full bg-gray-100">
                                    <div
                                        class="h-2 rounded-full bg-blue-600"
                                        :style="{
                                            width: `${Math.round((project.completed_tasks_count / Math.max(project.tasks_count, 1)) * 100)}%`,
                                        }"
                                    />
                                </div>
                            </Link>
                        </div>
                    </section>

                    <section>
                        <h2 class="mb-4 text-lg font-semibold text-gray-950">
                            Activity
                        </h2>
                        <ActivityFeed :items="activityLogs" />
                    </section>
                </div>
            </div>
        </div>

        <TaskModal
            :show="Boolean(selectedTask)"
            :task="selectedTask"
            @close="selectedTask = null"
        />
    </AuthenticatedLayout>
</template>
