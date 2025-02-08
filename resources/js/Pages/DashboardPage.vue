<script setup>
import DashboardComponent from '@/Components/Dashboard/DashboardComponent.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <DashboardComponent
            :title="$page.props.title"
            :links="$page.props.shortlinks"
            :locations="$page.props.locations"
        />
    </AppLayout>
</template>
