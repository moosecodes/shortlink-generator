<script setup>
import { VBtn, VCardActions, VCol } from 'vuetify/components';

const props = defineProps({
    data: Object,
});
</script>

<template>
    <v-card-actions :class="data.is_active ? 'bg-white' : 'bg-black'">
        <v-col cols="12" md="12">
            <div class="flex-column mb-2 flex flex-wrap">
                <a
                    :href="`/link/edit/byShortCode/${data.short_code}`"
                    :class="data.is_active ? 'text-blue-grey' : 'text-white'"
                    class="font-weight-bold mb-2"
                >
                    {{ data.title }}
                </a>

                <a
                    :href="data.short_url"
                    target="_blank"
                    class="font-weight-bold my-2"
                    :class="
                        data.is_active
                            ? 'text-black'
                            : 'text-blue-grey-lighten-2'
                    "
                >
                    {{ data.short_url }}
                </a>

                <a
                    :href="data.final_url"
                    :target="'_blank'"
                    :class="
                        data.is_active
                            ? 'text-black'
                            : 'text-blue-grey-lighten-2'
                    "
                >
                    {{ data.final_url }}
                </a>

                <a
                    :href="data.target_url"
                    :target="'_blank'"
                    :class="
                        data.is_active
                            ? 'text-black'
                            : 'text-blue-grey-lighten-2'
                    "
                >
                    {{ data.target_url }}
                </a>
            </div>

            <v-btn
                v-if="!route().current('link.analytics')"
                :href="route('link.analytics', { shortlink_id: data.id })"
                variant="outlined"
                :prepend-icon="
                    data.total_clicks + data.qr_scans > 0
                        ? 'mdi-signal-cellular-3'
                        : 'mdi-signal-off'
                "
                color="accent"
                class="font-weight-bold mr-2"
            >
                Analytics
            </v-btn>

            <v-btn
                variant="outlined"
                :prepend-icon="'mdi-eye'"
                :href="data.short_url"
                :color="data.is_active ? 'black' : 'white'"
                :target="'_blank'"
                class="font-weight-bold mr-2"
            >
                View Link
            </v-btn>

            <v-btn
                v-if="!route().current('link.update')"
                variant="outlined"
                :prepend-icon="'mdi-link-edit'"
                :color="data.is_active ? 'black' : 'white'"
                :href="`/link/edit/byShortCode/${data.short_code}`"
                class="font-weight-bold my-2"
            >
                Edit
            </v-btn>
        </v-col>
    </v-card-actions>
</template>
