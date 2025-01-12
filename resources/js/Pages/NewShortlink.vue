<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CreateShortlink from '@/Components/shortlinks/CreateShortlink.vue';
</script>

<template>
    <AppLayout title="New Shortlink">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                New Shortlink
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <CreateShortlink />
        </div>
    </AppLayout>
</template>
