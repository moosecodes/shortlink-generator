<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ListAllShortlinks from '@/Components/shortlinks/ListAllShortlinks.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <ListAllShortlinks />
        </div>
    </AppLayout>
</template>
