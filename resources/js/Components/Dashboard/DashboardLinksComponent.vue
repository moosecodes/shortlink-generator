<script setup>
import { VBtn, VRow, VCol } from 'vuetify/lib/components/index.mjs';

defineProps({
    links: Object,
});
</script>

<template>
    <v-row v-if="links?.length">
        <v-col cols="12" md="12">
            <div class="my-2 text-3xl font-semibold">Short Links</div>
        </v-col>

        <v-col
            v-for="{ short_code, short_url, title, id, is_active } in links"
            :key="short_code"
            cols="12"
            md="12"
        >
            <p>
                <a :href="short_url" target="_blank" class="font-weight-bold">
                    {{ title || 'No Title' }}
                </a>
            </p>

            <p>
                <a
                    :href="short_url"
                    target="_blank"
                    :class="is_active ? 'text-accent' : 'text-warning'"
                >
                    {{ short_code }}
                </a>
            </p>

            <p>
                <a :href="short_url" target="_blank">
                    {{ short_url }}
                </a>
            </p>

            <v-btn
                :href="route('link.analytics', { shortlink_id: id })"
                icon="mdi-signal"
                variant="outlined"
                :class="is_active ? 'text-accent' : 'text-warning'"
                class="m-4 ml-0"
            >
            </v-btn>

            <v-btn
                v-if="!route().current('link.update')"
                variant="outlined"
                icon="mdi-link-edit"
                :href="`/link/edit/byShortCode/${short_code}`"
                :class="is_active ? 'text-accent' : 'text-warning'"
                class="my-2"
            >
            </v-btn>
        </v-col>
    </v-row>

    <v-row v-else>
        <v-col cols="12" md="12">
            <v-btn
                class="text-2xl font-semibold"
                color="primary"
                :href="route('link.create')"
            >
                Create New Link
            </v-btn>
        </v-col>
    </v-row>
</template>
