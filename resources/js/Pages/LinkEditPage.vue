<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import LinkEditComponent from '@/Components/shortlinks/LinkEditComponent.vue';

const linkShortCode = defineProps(['linkShortCode']);
</script>

<template>
    <AppLayout title="Edit Shortlink">
        <LinkEditComponent :linkShortCode="linkShortCode"/>
    </AppLayout>
</template>
