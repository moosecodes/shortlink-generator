<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import UpdateShortlinkComponent from '@/Components/shortlinks/UpdateShortlinkComponent.vue';

const shortlink_id = defineProps(['shortlink_id']);
</script>

<template>
    <AppLayout title="Edit Shortlink">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Shortlink
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <UpdateShortlinkComponent :shortlink_id="shortlink_id"/>
            </div>
        </div>
    </AppLayout>
</template>
