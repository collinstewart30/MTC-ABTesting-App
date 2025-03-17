<script setup lang="ts">
import { defineProps } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import AbTestsDisplay from '../components/AbTestsDisplay.vue';

// Receive the `id` passed by Laravel route
const props = defineProps({
  test_id: {
    type: [String, Number],
    required: true,
  },
});

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: `Individual Test Data - A/B Test: ${props.test_id}`,
    href: '/ab-tests/{test_id}', // You may want to dynamically replace this as needed
  },
];
</script>

<template>

  <Head title="Individual Test Data" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <!-- Pass the `id` to the AbTestUserDisplay component -->
      <AbTestsDisplay :test_id="props.test_id" />
    </div>
  </AppLayout>
</template>
