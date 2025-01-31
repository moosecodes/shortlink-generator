<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { VBtn, VRow, VCol } from 'vuetify/lib/components/index.mjs';
import { Bar } from 'vue-chartjs'
import WorldMapComponent from '@/Components/shortlinks/WorldMapComponent.vue';

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
    graphs: Array,
    locations: Array,
});

const barOptions = {
    responsive: true,
    maintainAspectRatio: true,
    disableDefaultUI: true,
    scales: {
        x: {
            display: true,
            title: {
                display: false,
                text: 'Date'
            },
            grid: {
                color: 'rgba(128, 128, 128, 0)',
            },
        },
        y: {
            display: true,
            title: {
                display: true,
                text: 'Clicks'
            },
            grid: {
                color: 'rgba(128, 128, 128, 0.3)',
            },
        }
    },
    plugins: {
        legend: {
            display: false
        },
    },
};

const googleMapsApiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
</script>

<template>
    <AppLayout title="Dashboard">

        <v-row>
            <v-col cols="12" md="12">
                <p class="text-3xl font-semibold">Click Engagement</p>
            </v-col>
        </v-row>

        <v-row v-if="props?.graphs?.length && props?.locations?.length">
            <v-col cols="12" md="6">
                <div class="my-2 text-2xl font-semibold">Recent Activity</div>
                <div v-for="item in props.graphs" :key="item.shortCode"
                    :value="item.shortCode"
                    active-class="text-pink"
                    class="py-3"
                >
                    <p><b>{{ item.shortCode }} <small>({{ item.shortlink_id }})</small></b></p>

                    <v-btn :href="route('link.analytics', { shortlink_id: item.shortlink_id })" variant="flat" class="m-4">Analytics</v-btn>

                    <Bar :data="item" :options="barOptions" />

                </div>
            </v-col>

            <v-col cols="12" md="6">
                <WorldMapComponent
                    :googleMapsApiKey="googleMapsApiKey"
                    :locations="props.locations"
                />
            </v-col>
        </v-row>
    </AppLayout>
</template>
