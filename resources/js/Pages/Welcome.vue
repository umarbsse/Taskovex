<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    canLogin: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <Head title="Taskovex" />

    <main class="min-h-screen bg-gray-950 text-white">
        <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-6 py-6">
            <nav class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500 font-bold">
                        T
                    </span>
                    <span class="text-xl font-bold">Taskovex</span>
                </div>
                <div v-if="canLogin" class="flex items-center gap-3">
                    <Link
                        :href="route('login')"
                        class="rounded-md px-4 py-2 text-sm font-semibold text-gray-200 transition hover:bg-white/10"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-950 transition hover:bg-gray-100"
                    >
                        Register
                    </Link>
                </div>
            </nav>

            <section class="grid flex-1 items-center gap-10 py-12 lg:grid-cols-[1fr_460px]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-blue-300">
                        Smart task and workflow management
                    </p>
                    <h1 class="mt-5 max-w-3xl text-5xl font-bold leading-tight text-white">
                        Keep projects, tasks, reminders, and team activity moving.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-gray-300">
                        Taskovex gives teams a focused workspace for project planning,
                        Kanban execution, assignments, comments, attachments, reminders,
                        and activity history.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <Link
                            :href="route('register')"
                            class="rounded-md bg-blue-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-400"
                        >
                            Start managing work
                        </Link>
                        <Link
                            :href="route('login')"
                            class="rounded-md border border-white/20 px-5 py-3 text-sm font-semibold text-gray-100 transition hover:bg-white/10"
                        >
                            Open workspace
                        </Link>
                    </div>
                </div>

                <div class="rounded-xl border border-white/10 bg-white/10 p-4 shadow-2xl shadow-blue-950/40">
                    <div class="rounded-lg bg-white p-4 text-gray-900">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-950">Launch plan</p>
                                <p class="text-xs text-gray-500">Product workspace</p>
                            </div>
                            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                12 tasks
                            </span>
                        </div>
                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-lg bg-gray-50 p-3">
                                <p class="text-xs font-semibold uppercase text-gray-500">Todo</p>
                                <div class="mt-3 space-y-2">
                                    <div class="rounded-md bg-white p-3 text-sm shadow-sm">Outline onboarding</div>
                                    <div class="rounded-md bg-white p-3 text-sm shadow-sm">Confirm report fields</div>
                                </div>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-3">
                                <p class="text-xs font-semibold uppercase text-gray-500">In progress</p>
                                <div class="mt-3 space-y-2">
                                    <div class="rounded-md bg-white p-3 text-sm shadow-sm">Build Kanban flow</div>
                                    <div class="rounded-md bg-white p-3 text-sm shadow-sm">Schedule reminders</div>
                                </div>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-3">
                                <p class="text-xs font-semibold uppercase text-gray-500">Review</p>
                                <div class="mt-3 space-y-2">
                                    <div class="rounded-md bg-white p-3 text-sm shadow-sm">Finalize roles</div>
                                </div>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-3">
                                <p class="text-xs font-semibold uppercase text-gray-500">Completed</p>
                                <div class="mt-3 space-y-2">
                                    <div class="rounded-md bg-white p-3 text-sm shadow-sm">Create project shell</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</template>
