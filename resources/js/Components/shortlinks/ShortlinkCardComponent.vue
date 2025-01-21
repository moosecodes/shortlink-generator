<script setup>
import { reactive, onMounted } from 'vue';
import { VRow } from 'vuetify/components';
import dayjs from 'dayjs';
import ShortlinkCardDetailsComponent from './ShortlinkCardDetailsComponent.vue';

defineProps({
    filteredShortlinks: Object,
});
const state = reactive({
    shortlinks: [],
    redirects: [],
    linkFilter: 'All',
});

const redirectedUrls = async () => {
    try {
        const response = await axios.post(`/api/urls`, { shortlinks: state.shortlinks });
        const urls = response.data.shortlink_redirect_urls;
        urls.forEach(url => {
            state.redirects?.push({short_code: url.short_code, redirect: url.url});
        });
    } catch (error) {
        console.error('Error fetching redirected URLs:', error);
    }
}

onMounted(() => {
    redirectedUrls();
});
</script>

<template>
    <v-row v-for="shortlink in filteredShortlinks" :key="shortlink.id">
        <ShortlinkCardDetailsComponent :shortlink="shortlink" :redirects="state.redirects" />
    </v-row>
</template>
