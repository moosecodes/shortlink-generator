<script setup>
import LandingPageLayout from '@/Layouts/LandingPageLayout.vue';
import LandingPageFeaturesComponent from '@/Components/shortlinks/LandingPageFeaturesComponent.vue';
import LandingPageGeneratorsComponent from '@/Components/shortlinks/LandingPageGeneratorsComponent.vue';

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
