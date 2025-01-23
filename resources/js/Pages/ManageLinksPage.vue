<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ManageLinksComponent from '@/Components/shortlinks/ManageLinksComponent.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <ManageLinksComponent />
        </div>
    </AppLayout>
</template>
