<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import LinkEditComponent from '@/Components/shortlinks/LinkEditComponent.vue';

const linkShortCode = defineProps(['linkShortCode']);
</script>

<template>
    <AppLayout title="Edit Shortlink">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <LinkEditComponent :linkShortCode="linkShortCode"/>
            </div>
        </div>
    </AppLayout>
</template>
