<script setup>
import LandingPageFeaturesComponent from '@/Components/LandingPage/LandingPageFeaturesComponent.vue';
import LandingPageGeneratorsComponent from '@/Components/LandingPage/LandingPageGeneratorsComponent.vue';
import LandingPageLayout from '@/Layouts/LandingPageLayout.vue';

defineProps({
    title: String,
});
</script>

<template>
    <LandingPageLayout title="Landing Page">
        <LandingPageGeneratorsComponent />

        <LandingPageFeaturesComponent />
    </LandingPageLayout>
</template>
