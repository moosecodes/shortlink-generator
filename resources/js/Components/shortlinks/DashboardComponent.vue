<script setup>
import SubMenuComponent from './SubMenuComponent.vue';
import PageHeaderComponent from './PageHeaderComponent.vue';
import DashboardLinksComponent from './DashboardLinksComponent.vue';

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
