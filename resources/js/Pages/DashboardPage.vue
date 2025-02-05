<script setup>
import DashboardComponent from '@/Components/shortlinks/DashboardComponent.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    shortlinks: Array,
    locations: Array,
});
</script>

<template>
    <AppLayout title="Dashboard">
        <DashboardComponent />
    </AppLayout>
</template>
