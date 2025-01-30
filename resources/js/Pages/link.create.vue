<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import NewLinkComponent from '@/Components/shortlinks/NewLinkComponent.vue';
</script>

<template>
    <AppLayout title="New Link">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <NewLinkComponent />
        </div>
    </AppLayout>
</template>
