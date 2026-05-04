<script setup lang="ts">
import Modal from '@/Components/Modal.vue';
import type { TaskovexTask } from '@/types/taskovex';
import { Link, useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps<{
    show: boolean;
    task: TaskovexTask | null;
}>();

defineEmits<{
    close: [];
}>();

const form = useForm({
    body: '',
});

watch(
    () => props.show,
    () => {
        if (!props.show) {
            form.reset();
            form.clearErrors();
        }
    },
);

const submitComment = () => {
    if (!props.task) {
        return;
    }

    form.post(route('tasks.comments.store', props.task.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Modal :show="show" max-width="2xl" @close="$emit('close')">
        <div v-if="task" class="p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        {{ task.priority }} priority
                    </p>
                    <h2 class="mt-1 text-xl font-semibold text-gray-900">
                        {{ task.title }}
                    </h2>
                    <p class="mt-2 text-sm capitalize text-gray-500">
                        {{ task.status.replace('_', ' ') }}
                    </p>
                </div>
                <button
                    type="button"
                    class="rounded-md px-2 py-1 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-800"
                    @click="$emit('close')"
                >
                    Close
                </button>
            </div>

            <p v-if="task.description" class="mt-5 whitespace-pre-line text-sm leading-6 text-gray-700">
                {{ task.description }}
            </p>

            <div class="mt-6 grid gap-3 text-sm sm:grid-cols-3">
                <div class="rounded-lg bg-gray-50 p-3">
                    <p class="text-xs font-medium uppercase text-gray-500">Assigned</p>
                    <p class="mt-1 font-medium text-gray-900">
                        {{ task.assigned_user?.name ?? 'Unassigned' }}
                    </p>
                </div>
                <div class="rounded-lg bg-gray-50 p-3">
                    <p class="text-xs font-medium uppercase text-gray-500">Due</p>
                    <p class="mt-1 font-medium text-gray-900">
                        {{ task.due_date ?? 'No date' }}
                    </p>
                </div>
                <div class="rounded-lg bg-gray-50 p-3">
                    <p class="text-xs font-medium uppercase text-gray-500">Files</p>
                    <p class="mt-1 font-medium text-gray-900">
                        {{ task.attachments?.length ?? 0 }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-2">
                <Link
                    :href="route('tasks.edit', task.id)"
                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Edit task
                </Link>
                <Link
                    :href="route('tasks.complete', task.id)"
                    method="patch"
                    as="button"
                    class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    Mark completed
                </Link>
            </div>

            <div class="mt-8">
                <h3 class="text-sm font-semibold text-gray-900">Comments</h3>
                <div class="mt-3 space-y-3">
                    <div
                        v-for="comment in task.comments ?? []"
                        :key="comment.id"
                        class="rounded-lg bg-gray-50 p-3 text-sm"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-medium text-gray-900">
                                {{ comment.user.name }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ comment.created_at }}
                            </span>
                        </div>
                        <p class="mt-2 whitespace-pre-line text-gray-700">
                            {{ comment.body }}
                        </p>
                    </div>
                    <p v-if="!task.comments?.length" class="text-sm text-gray-500">
                        No comments yet.
                    </p>
                </div>

                <form class="mt-4 space-y-2" @submit.prevent="submitComment">
                    <textarea
                        v-model="form.body"
                        rows="3"
                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Add a comment"
                    />
                    <p v-if="form.errors.body" class="text-sm text-red-600">
                        {{ form.errors.body }}
                    </p>
                    <button
                        type="submit"
                        class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-800 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        Post comment
                    </button>
                </form>
            </div>
        </div>
    </Modal>
</template>
