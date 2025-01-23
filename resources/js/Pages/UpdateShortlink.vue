<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import UpdateLinkComponent from '@/Components/shortlinks/UpdateLinkComponent.vue';

const shortlink_id = defineProps(['shortlink_id']);
</script>

<template>
    <AppLayout title="Edit Shortlink">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <UpdateLinkComponent :shortlink_id="shortlink_id"/>
            </div>
        </div>
    </AppLayout>
</template>
