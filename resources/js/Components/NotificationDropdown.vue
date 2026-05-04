<script setup lang="ts">
import Dropdown from '@/Components/Dropdown.vue';
import type { PageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage<PageProps>();

const notifications = computed(
    () =>
        page.props.notifications ?? {
            items: [],
            unread: 0,
        },
);
</script>

<template>
    <Dropdown align="right" width="80">
        <template #trigger>
            <button
                type="button"
                class="relative inline-flex h-10 w-10 items-center justify-center rounded-md border border-gray-200 bg-white text-gray-600 transition hover:border-gray-300 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                aria-label="Notifications"
            >
                <svg
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" />
                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
                <span
                    v-if="notifications.unread"
                    class="absolute -right-1 -top-1 min-w-5 rounded-full bg-blue-600 px-1.5 text-xs font-semibold text-white"
                >
                    {{ notifications.unread }}
                </span>
            </button>
        </template>

        <template #content>
            <div class="w-80 p-2">
                <div class="px-3 py-2 text-sm font-semibold text-gray-900">
                    Notifications
                </div>
                <div
                    v-if="notifications.items.length"
                    class="max-h-80 overflow-y-auto"
                >
                    <div
                        v-for="notification in notifications.items"
                        :key="notification.id"
                        class="rounded-md px-3 py-2 text-sm hover:bg-gray-50"
                    >
                        <p class="font-medium text-gray-800">
                            {{ notification.message }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ notification.created_at }}
                        </p>
                    </div>
                </div>
                <div v-else class="px-3 py-6 text-center text-sm text-gray-500">
                    No notifications yet.
                </div>
            </div>
        </template>
    </Dropdown>
</template>
