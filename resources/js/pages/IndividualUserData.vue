<script setup lang="ts">
import { defineProps } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import AbTestUserDisplay from '@/components/AbTestUserDisplay.vue';
import AbConversionUserDisplay from '@/components/AbConversionUserDisplay.vue';

// Receive the `id` passed by Laravel route
const props = defineProps({
  user_session_id: {
    type: [String, Number],
    required: true,
  },
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: `Individual User Data - Session ID: ${props.user_session_id}`,
        href: '/user-data/{user_session_id}', // You may want to dynamically replace this as needed
    },
];
</script>

<template>
  <Head title="Individual User Data" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <!-- Pass the `id` to the AbTestUserDisplay component -->
      <AbTestUserDisplay :user_session_id="props.user_session_id" />
      <AbConversionUserDisplay :user_session_id="props.user_session_id" />
    </div>
  </AppLayout>
</template>
