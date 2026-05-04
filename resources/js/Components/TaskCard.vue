<script setup lang="ts">
import type { TaskovexTask } from '@/types/taskovex';

defineProps<{
    task: TaskovexTask;
}>();

defineEmits<{
    open: [task: TaskovexTask];
}>();

const priorityClass = (priority: string) =>
    ({
        low: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        medium: 'bg-sky-50 text-sky-700 ring-sky-200',
        high: 'bg-amber-50 text-amber-700 ring-amber-200',
        urgent: 'bg-rose-50 text-rose-700 ring-rose-200',
    })[priority] ?? 'bg-gray-50 text-gray-700 ring-gray-200';
</script>

<template>
    <div
        class="cursor-grab rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-gray-300 hover:shadow"
        @click="$emit('open', task)"
    >
        <div class="flex items-start justify-between gap-3">
            <h3 class="text-sm font-semibold leading-5 text-gray-900">
                {{ task.title }}
            </h3>
            <span
                class="rounded-full px-2 py-0.5 text-xs font-medium ring-1"
                :class="priorityClass(task.priority)"
            >
                {{ task.priority }}
            </span>
        </div>
        <p
            v-if="task.description"
            class="mt-2 line-clamp-2 text-sm leading-5 text-gray-600"
        >
            {{ task.description }}
        </p>
        <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-gray-500">
            <span v-if="task.assigned_user" class="rounded bg-gray-100 px-2 py-1">
                {{ task.assigned_user.name }}
            </span>
            <span v-if="task.due_date" class="rounded bg-gray-100 px-2 py-1">
                Due {{ task.due_date }}
            </span>
            <span
                v-if="task.subtasks?.length"
                class="rounded bg-gray-100 px-2 py-1"
            >
                {{ task.subtasks.filter((subtask) => subtask.is_completed).length }}/{{ task.subtasks.length }}
            </span>
        </div>
    </div>
</template>
