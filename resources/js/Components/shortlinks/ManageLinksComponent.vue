<script setup>
import { computed, reactive, onMounted } from 'vue';
import { VBtn, VRow, VCol, VSelect } from 'vuetify/components';
import { router } from '@inertiajs/vue3';
import ShortlinkCardComponent from './ShortlinkCardComponent.vue';
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
    auth: Object,
    flash: Object,
});

const state = reactive({
    userId: page.props.auth.user?.id,
    shortlinks: [],
    redirects: [],
    linkFilter: 'All',
});

const navigateTo = (routeName) => {
    router.get(route(routeName));
};

const fetchShortlinks = async () => {
    try {
        const response = await axios.post('/api/shortlinks/show/all' , {
            userId: state.userId,
        });
        console.log(response.data);
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

const filteredShortlinks = computed(() => {
    if (state.linkFilter === 'All') {
        return state.shortlinks.slice().reverse();
    } else if (state.linkFilter === 'Active') {
        return state.shortlinks.filter(link => link.is_active).reverse();
    } else {
        return state.shortlinks.filter(link => !link.is_active).reverse();
    }
});

onMounted(() => {
    fetchShortlinks();
    redirectedUrls();
});
</script>

<template>
        <v-row>
            <v-col>
                <h1 class="text-2xl font-bold">{{ state.shortlinks.length ? "Manage Shortlinks" : "No Shortlinks" }}</h1>
                <div v-if="!state.shortlinks.length">
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

            <ShortlinkCardComponent :filteredShortlinks="filteredShortlinks" />
</template>
