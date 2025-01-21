<script setup>
import LandingPageLayout from '@/Layouts/LandingPageLayout.vue';
import LandingPageComponent from '@/Components/shortlinks/LandingPageComponent.vue';
</script>

<template>
    <LandingPageLayout title="Landing Page">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Landing Page
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
           <LandingPageComponent />
        </div>
    </LandingPageLayout>
</template>
