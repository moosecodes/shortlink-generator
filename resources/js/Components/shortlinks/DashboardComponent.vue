<script setup>
import { Link } from '@inertiajs/vue3';
import { VBtn, VRow, VCol, VCard } from 'vuetify/lib/components/index.mjs';
import SubMenuComponent from './SubMenuComponent.vue';
const navigateTo = (routeName) => {
    router.get(route(routeName));
};
</script>

<template>
    <img
        class="size-100 contrast- rounded-full"
        src="https://picsum.photos/600"
        alt=""
    />
</template>
