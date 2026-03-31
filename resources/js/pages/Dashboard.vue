<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CanvasBoard from '@/components/CanvasBoard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { home } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: home(),
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <CanvasBoard />
        </div>
    </AppLayout>
</template>
