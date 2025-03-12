<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import ListAbTests from '@/components/ListAbTests.vue';
//import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'A/B Tests',
        href: '/ab-tests',
    },
];
</script>

<template>

    <Head title="A/B Tests" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ListAbTests />
        </div>
    </AppLayout>
</template>
