<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import QrCodeComponent from '@/Components/shortlinks/QrCodeComponent.vue';
import FormTestComponent from '@/Components/shortlinks/FormTestComponent.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <FormTestComponent />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            Dashboard: <QrCodeComponent input="mustafa" />
        </div>
    </AppLayout>
</template>
