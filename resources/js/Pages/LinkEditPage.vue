<script setup>
import LinkEditComponent from '@/Components/Link/LinkEditComponent.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const linkShortCode = defineProps(['linkShortCode']);
</script>

<template>
    <AppLayout title="Edit Shortlink">
        <LinkEditComponent :linkShortCode="linkShortCode" />
    </AppLayout>
</template>
