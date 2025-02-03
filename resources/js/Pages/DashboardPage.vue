<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { VBtn, VRow, VCol } from 'vuetify/lib/components/index.mjs';
import { Bar } from 'vue-chartjs'
import WorldTrafficComponent from '@/Components/shortlinks/WorldTrafficComponent.vue';

import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  BarElement
} from 'chart.js'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  BarElement,
)

const props = defineProps({
    shortlinks: Array,
    locations: Array,
});

const editLink = (shortCode) => {
    window.location.href = `/link/edit/byShortCode/${shortCode}`
};
</script>

<template>
    <AppLayout title="Dashboard">

        <v-row>
            <v-col cols="12" md="12">
                <p class="text-3xl font-semibold">Click Engagement</p>
            </v-col>
        </v-row>

        <v-row v-if="props?.shortlinks?.length">
            <v-col cols="12" md="12">
                <div class="my-2 text-3xl font-semibold">Recent Activity</div>
            </v-col>

            <v-col v-for="item in props.shortlinks" :key="item.shortCode" cols="12" md="12">
{{ item }}
                <div
                    :value="item.shortCode"
                    active-class="text-pink"
                    class="py-3"
                >
                    <p>
                        <v-btn :href="item.short_url" target="_blank">
                            {{ item.short_url }}
                        </v-btn>
                    </p>

                    <v-btn
                        :href="route('link.analytics', { shortlink_id: item.id })"
                        prepend-icon="mdi-signal"
                        variant="outlined"
                        class="m-4"
                    >
                        Analytics
                    </v-btn>

                    <v-btn
                        v-if="!route().current('link.update')"
                        variant="outlined"
                        prepend-icon="mdi-link-edit"
                        @click="editLink(item.short_code)"
                        class="m-2">
                        Edit Link
                    </v-btn>

                </div>
            </v-col>

            <v-col cols="12" md="12" v-if="props.shortlinks.length">
                <WorldTrafficComponent
                    :locations="props.locations"
                />
            </v-col>
        </v-row>
    </AppLayout>
</template>
