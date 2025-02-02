<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, reactive, onMounted } from 'vue';
import { VBtn, VRow, VCol, VSelect } from 'vuetify/components';
import { usePage } from '@inertiajs/vue3'
import LinkDetailsComponent from '@/Components/shortlinks/LinkDetailsComponent.vue';
import { fetchUserShortlinks, navigateTo } from '@/Components/shortlinks/requests';

const page = usePage() // TODO: look up docs for usePage() and useOther()

const props = defineProps({
    auth: Object,
    flash: Object,
});

const state = reactive({
    shortlinks: [],
    linkFilter: 'All',
});

const filteredShortlinks = computed(() => {
    const { linkFilter, shortlinks } = state;

    if(!shortlinks) {
        return [];
    }
    if (linkFilter === 'All') {
        return shortlinks.slice().reverse();
    } else if (linkFilter === 'Active') {
        return shortlinks.filter(link => link.is_active).reverse();
    } else {
        return shortlinks.filter(link => !link.is_active).reverse();
    }
});

onMounted(async () => {
    console.log(page);
    state.shortlinks = await fetchUserShortlinks();
});
</script>

<template>
    <AppLayout title="Manage Links">
        <v-row>
            <v-col cols="12" md="12">
                <h1 class="text-3xl font-semibold">Manage Links</h1>
                <p v-if="!state.shortlinks?.length">
                    No shortlinks currently exist. Create a new link to get started!
                </p>
            </v-col>

            <v-col align="end" cols="12" md="12">
                <v-btn
                    prepend-icon="mdi-plus"
                    color="primary"
                    class=""
                    @click="navigateTo('link.create')">
                    New Link
                </v-btn>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <h2 class="my-2 font-bold">{{ state.linkFilter }} Links ({{ filteredShortlinks?.length }}/{{ state.shortlinks?.length }})</h2>
            </v-col>

            <v-col>
                <v-select
                    v-model="state.linkFilter"
                    :items="['All', 'Active', 'Inactive']"
                    label="Filter Shortlinks"
                    ></v-select>
            </v-col>
        </v-row>

        <LinkDetailsComponent v-if="filteredShortlinks" :filteredShortlinks="filteredShortlinks" />
    </AppLayout>
</template>
