<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, reactive, onMounted } from 'vue';
import { VBtn, VRow, VCol, VSelect } from 'vuetify/components';
import { usePage } from '@inertiajs/vue3'
import CardDetails from '@/Components/shortlinks/CardDetails.vue';
import { fetchShortlinks, redirectedUrls, navigateTo } from '@/Components/shortlinks/requests';

const page = usePage()

const props = defineProps({
    auth: Object,
    flash: Object,
});

const state = reactive({
    shortlinks: [],
    redirects: [],
    linkFilter: 'All',
});

const filteredShortlinks = computed(() => {
    const { linkFilter, shortlinks } = state;

    if (linkFilter === 'All') {
        return shortlinks.slice().reverse();
    } else if (linkFilter === 'Active') {
        return shortlinks.filter(link => link.is_active).reverse();
    } else {
        return shortlinks.filter(link => !link.is_active).reverse();
    }
});

onMounted(async () => {
    state.shortlinks = await fetchShortlinks(page.props.auth.user?.id);
    state.redirects = await redirectedUrls(state.shortlinks);
});
</script>

<template>
    <AppLayout title="Manage Shortlinks">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <v-row>
                <v-col cols="12">
                    <h1 class="text-2xl font-bold">Manage Shortlinks</h1>
                    <p v-if="!state.shortlinks.length">
                        No shortlinks currently exist. Create a new link to get started!
                    </p>
                </v-col>
                <v-col align="end" cols="12">
                    <v-btn
                        prepend-icon="mdi-plus"
                        color="primary"
                        class=""
                        @click="navigateTo('CreateLinkPage')">
                        New Link
                    </v-btn>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <h2 class="my-2 font-bold">{{ state.linkFilter }} Shortlinks ({{ filteredShortlinks.length }}/{{ state.shortlinks.length }})</h2>
                </v-col>

                <v-col>
                    <v-select
                        v-model="state.linkFilter"
                        :items="['All', 'Active', 'Inactive']"
                        label="Filter Shortlinks"
                        ></v-select>
                </v-col>
            </v-row>

            <v-row v-for="shortlink in filteredShortlinks" :key="shortlink.id">
                <CardDetails :shortlink="shortlink" :redirects="state.redirects" />
            </v-row>
        </div>
    </AppLayout>
</template>
