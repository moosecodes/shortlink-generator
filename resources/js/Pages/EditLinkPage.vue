<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import LinkEditComponent from '@/Components/shortlinks/LinkEditComponent.vue';

const shortlink_id = defineProps(['shortlink_id']);
</script>

<template>
    <AppLayout title="Edit Shortlink">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <LinkEditComponent :shortlink_id="shortlink_id"/>
            </div>
        </div>
    </AppLayout>
</template>
