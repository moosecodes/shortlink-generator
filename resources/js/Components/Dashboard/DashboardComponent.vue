<script setup>
import DashboardLinksComponent from '@/Components/Dashboard/DashboardLinksComponent.vue';
import SubMenuComponent from '@/Components/Navigation/SubMenuComponent.vue';
import PageHeaderComponent from '@/Components/PageHeaderComponent.vue';

defineProps({
    title: String,
    links: Object,
    locations: Object,
});
</script>

<template>
    <PageHeaderComponent />

    <SubMenuComponent />

    <DashboardLinksComponent :links="links" />
</template>
