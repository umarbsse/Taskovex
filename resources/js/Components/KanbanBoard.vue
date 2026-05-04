<script setup lang="ts">
import TaskCard from '@/Components/TaskCard.vue';
import type { TaskovexTask } from '@/types/taskovex';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    tasks: TaskovexTask[];
    statuses: string[];
}>();

const emit = defineEmits<{
    open: [task: TaskovexTask];
}>();

const draggedTaskId = ref<number | null>(null);

const columns = computed(() =>
    props.statuses.map((status) => ({
        status,
        label: status.replace('_', ' '),
        tasks: props.tasks.filter((task) => task.status === status),
    })),
);

const onDrop = (status: string) => {
    if (!draggedTaskId.value) {
        return;
    }

    const task = props.tasks.find((item) => item.id === draggedTaskId.value);
    draggedTaskId.value = null;

    if (!task || task.status === status) {
        return;
    }

    router.patch(
        route('tasks.status', task.id),
        { status },
        {
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <div class="grid gap-4 xl:grid-cols-4">
        <section
            v-for="column in columns"
            :key="column.status"
            class="min-h-96 rounded-lg border border-gray-200 bg-gray-50 p-3"
            @dragover.prevent
            @drop="onDrop(column.status)"
        >
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-sm font-semibold capitalize text-gray-800">
                    {{ column.label }}
                </h2>
                <span class="rounded-full bg-white px-2 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-gray-200">
                    {{ column.tasks.length }}
                </span>
            </div>

            <div class="space-y-3">
                <TaskCard
                    v-for="task in column.tasks"
                    :key="task.id"
                    :task="task"
                    draggable="true"
                    @dragstart="draggedTaskId = task.id"
                    @dragend="draggedTaskId = null"
                    @open="emit('open', $event)"
                />
                <div
                    v-if="!column.tasks.length"
                    class="rounded-lg border border-dashed border-gray-300 bg-white p-5 text-center text-sm text-gray-500"
                >
                    No tasks.
                </div>
            </div>
        </section>
    </div>
</template>
