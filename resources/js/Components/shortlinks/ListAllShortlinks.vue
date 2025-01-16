<script setup>
import { ref, computed, reactive, onMounted } from 'vue';
import { VBtn, VChip, VRow, VCol, VCard, VCardItem, VCardActions, VDataTableServer, VTextField, VContainer, VSelect } from 'vuetify/components';
import { router } from '@inertiajs/vue3';

const state = reactive({
    shortlinks: [],
    redirects: [],
    linkFilter: 'All',
});

const shortlinks = ref([]);

const editShortlink = (shortlink) => {
    window.location.href = `/shortlinks/edit/${shortlink.short_code}`
};

const deleteShortlink = async(shortlink) => {
    const confirmDelete = confirm(`Are you sure you want to delete this shortlink ${shortlink.short_code}?`);
    if (confirmDelete) {
        try {
            await axios.delete(`/api/shortlinks/delete/${shortlink.short_code}`);
            window.location.reload();
        } catch (error) {
            console.error('Error deleting shortlink:', error);
        }
    }
}

const toggleActivation = async (shortlink) => {
    try {
        const route = shortlink.is_active
            ? `/api/shortlinks/deactivate/${shortlink.short_code}`
            : `/api/shortlinks/activate/${shortlink.short_code}`;

        await axios.patch(route);

        shortlink.is_active = !shortlink.is_active;
    } catch (error) {
        console.error('Error toggling activation:', error);
    }
};

const filteredShortlinks = computed(() => {
    if (state.linkFilter === 'All') {
        return state.shortlinks.slice().reverse();
    } else if (state.linkFilter === 'Active') {
        return state.shortlinks.filter(link => link.is_active).reverse();
    } else {
        return state.shortlinks.filter(link => !link.is_active).reverse();
    }
});

const navigateTo = (routeName) => {
    router.get(route(routeName));
};

const fetchShortlinks = async () => {
    try {
        const response = await axios.get('/api/shortlinks/show/all');
        state.shortlinks = response.data;
    } catch (error) {
        console.error('Error fetching shortlinks:', error);
    }
};

const redirectedUrls = async () => {
    try {
        const response = await axios.post(`/api/shortlinks/redirect/urls`, { shortlinks: state.shortlinks });
        const urls = response.data.shortlink_redirect_urls;
        urls.forEach(url => {
            state.redirects?.push({short_code: url.short_code, redirect: url.url});
        });
    } catch (error) {
        console.error('Error fetching redirected URLs:', error);
    }
}

onMounted(() => {
    fetchShortlinks();
    redirectedUrls();
});
</script>

<template>
        <v-row>
            <v-col>
                <h1 class="text-2xl font-bold">{{ shortlinks.length ? "Manage Shortlinks" : "No Shortlinks" }}</h1>
                <div v-if="!shortlinks.length">
                    Create new shortlinks to get started!
                </div>
            </v-col>

            <v-col align="end">
                <v-btn
                    prepend-icon="mdi-plus"
                    color="indigo"
                    class=""
                    @click="navigateTo('newShortlink')">
                    New Shortlink
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
            <v-col cols="12" md="12">
                <v-card
                    :color="shortlink?.is_active ? 'indigo' : 'indigo darken-4'"
                    :variant="shortlink?.is_active ? 'elevated' : 'tonal'"
                    class="mx-auto"
                    target="_blank"
                >
                    <v-card-item>
                        <div class="text-overline mb-1 flex justify-between align-center">
                            <div>
                                <v-chip class="my-2 mr-2"><b>{{ shortlink.short_code }}</b></v-chip>
                                <v-chip class="my-2">
                                    {{ shortlink.is_active ? 'Active' : 'Inactive' }}
                                </v-chip>
                            </div>
                            <div>
                                <v-chip class="my-2 mr-2">{{ shortlink.total_clicks }} Clicks</v-chip>
                                <v-chip class="my-2 mr-2">{{ shortlink.unique_clicks }} Unique Clicks</v-chip>
                            </div>
                        </div>
                    </v-card-item>

                    <v-card-item>
                        <div class="d-flex flex-wrap">
                            <v-chip  class="text-caption my-2 mr-2 item-justify-start">
                                <a :href="state.redirects?.filter(redirect => shortlink.short_code === redirect.short_code)[0]?.redirect" target="_blank">{{ state.redirects?.filter(redirect => shortlink.short_code === redirect.short_code)[0]?.redirect }}</a>
                            </v-chip>
                        </div>
                    </v-card-item>

                    <v-card-actions class="d-flex flex-wrap bg-indigo-darken-3">
                        <small class="mx-2">Created: <b>{{ new Date(shortlink.created_at).toLocaleString() }}</b></small>
                        <small class="mx-2">Updated: <b>{{ new Date(shortlink.updated_at).toLocaleString() }}</b></small>
                    </v-card-actions>

                    <v-card-actions class="d-flex justify-end bg-indigo-darken-3">
                        <div class="d-flex flex-wrap">
                            <v-btn
                                variant="outlined"
                                :href="`/api/shortlinks/redirect/${shortlink.short_code}`"
                                target="_blank"
                                prepend-icon="mdi-eye"
                                class="m-2">
                                View Link
                            </v-btn>

                            <v-btn
                                variant="outlined"
                                prepend-icon="mdi-link"
                                @click="editShortlink(shortlink)"
                                class="m-2">
                                Edit Link
                            </v-btn>

                            <v-btn
                                variant="flat"
                                :prepend-icon="shortlink.is_active ? 'mdi-cancel' : 'mdi-connection'"
                                :color="shortlink.is_active ? 'error' : 'success'"
                                @click="toggleActivation(shortlink)"
                                class="m-2">
                                {{ shortlink.is_active ? 'Disable' : 'Enable' }}
                            </v-btn>

                            <v-btn
                                v-if="!shortlink.is_active"
                                variant="flat"
                                prepend-icon="mdi-delete"
                                color="error"
                                @click="deleteShortlink(shortlink)"
                                class="m-2">
                                Delete
                            </v-btn>
                        </div>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
</template>
