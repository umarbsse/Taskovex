<script setup lang="ts">
import type { ActivityLogItem } from '@/types/taskovex';

defineProps<{
    items: ActivityLogItem[];
}>();
</script>

<template>
    <div class="space-y-3">
        <div
            v-for="item in items"
            :key="item.id"
            class="rounded-lg border border-gray-200 bg-white p-4"
        >
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-900">
                        {{ item.description }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ item.user }}
                        <span v-if="item.project">in {{ item.project }}</span>
                    </p>
                </div>
                <span class="shrink-0 text-xs text-gray-500">
                    {{ item.created_at }}
                </span>
            </div>
        </div>
        <div
            v-if="!items.length"
            class="rounded-lg border border-dashed border-gray-300 bg-white p-6 text-center text-sm text-gray-500"
        >
            Activity will appear here.
        </div>
    </div>
</template>
