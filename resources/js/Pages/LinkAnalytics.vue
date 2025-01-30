<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { GoogleMap, Marker, AdvancedMarker } from 'vue3-google-map'
import { VRow, VCol, VExpansionPanels, VExpansionPanel } from 'vuetify/lib/components/index.mjs';
import customMapStyles from './googleMapStyles';

import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'

import { Line } from 'vue-chartjs'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
)

const props = defineProps({
    graphs: Array,
    locations: Array,
});

const options = {
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
                color: 'rgba(200, 200, 200, 0.1)', // X-axis grid line color
            },
        },
        y: {
            display: true,
            title: {
                display: true,
                text: 'Clicks'
            },
            grid: {
                color: 'rgba(200, 200, 200, 0.1)', // X-axis grid line color
            },
        }
    },
    plugins: {
        legend: {
            display: false
        },
    },
};

const deDupedLocations = () => {
    if (!props.locations) {
        return [];
    }
    return props.locations.filter((location, index, self) =>
        index === self.findIndex((t) => (
            t.country_name === location.country_name
        ))
    );
};

const googleMapsApiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
</script>

<template>
    <AppLayout title="Dashboard">
        <v-row>
            <v-col cols="12" md="12">
                <p class="text-3xl font-semibold">Link Analytics</p>
            </v-col>
        </v-row>

        <div v-if="props?.graphs?.length">
            <v-row>
                <v-col col="12" md="4" v-for="(graph, i) in Array.from(props.graphs)" :key="i">
                    <p class="text-2xl font-semibold">{{ graph.shortCode }}</p>
                    <Line :data="graph" :options="options" class="my-4" />
                </v-col>
            </v-row>

            <v-row v-if="props?.locations?.length">
                <v-col cols="12" md="12">
                    <div class="mt-4 mb-2">Locations</div>
                    <GoogleMap
                            :api-key="googleMapsApiKey"
                            style="width: 100%; height: 400px"
                            :styles="customMapStyles.darkGold"
                            :zoom="2"
                            :disableDefaultUi="true"
                        >
                        <Marker
                            v-for="(location, i) in deDupedLocations()" :key="i"
                            :options="{
                                position: {
                                    lat: parseFloat(location.latitude),
                                    lng: parseFloat(location.longitude)
                            }}"
                        />
                    </GoogleMap>
                    <v-expansion-panels>
                        <v-expansion-panel
                            v-for="(location, i) in deDupedLocations()" :key="i"
                            :title="location.country_name"
                            :text="`Timezone: ${location.timezone}`"
                        />
                    </v-expansion-panels>
                </v-col>
            </v-row>
        </div>
    </AppLayout>
</template>
