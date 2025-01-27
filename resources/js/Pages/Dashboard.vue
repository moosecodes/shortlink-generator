<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { GoogleMap, Marker, AdvancedMarker } from 'vue3-google-map'
import { VBtn, VRow, VCol, VToolbarTitle, VSpacer, VToolbar, VListItemTitle, VListItemAction, VIcon, VList, VListItem, VListItemSubtitle, VCard } from 'vuetify/lib/components/index.mjs';
import customMapStyles from './googleMapStyles';
import { ref } from 'vue';
import { Bar } from 'vue-chartjs'

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

const selected = ref([1]);

const shortlinkId = '9e09b2b0-8df2-4071-a77d-e2c21c661a15';
</script>

<template>
    <AppLayout title="Dashboard">
        <v-row>
            <v-col cols="12" md="12">
                <p class="text-3xl font-semibold">Click Engagement</p>
            </v-col>
        </v-row>

        <div v-if="props?.graphs?.length">
            <v-row v-if="props?.locations?.length">
                <v-col cols="12" md="12">
                    <div class="my-2 text-2xl font-semibold">Locations</div>
                    <p v-for="(location, i) in deDupedLocations()" :key="i" class="my-2">{{ location.country_name + ' - ' + location.timezone }}</p>
                    <GoogleMap
                        :api-key="googleMapsApiKey"
                        style="width: 100%; height: 400px"
                        :styles="customMapStyles.assasin"
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
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" md="12">
                    <v-card>
                        <v-toolbar color="pink">

                            <v-toolbar-title>Short Codes</v-toolbar-title>

                            <v-spacer></v-spacer>

                        </v-toolbar>

                        <v-list v-model:selected="selected" select-strategy="leaf">
                            <v-list-item
                                v-for="item in props.graphs"
                                :key="item.shortCode"
                                :value="item.shortCode"
                                active-class="text-pink"
                                class="py-3"
                            >
                                <v-btn :href="route('link.graphs', { shortlink_id: item.shortlink_id })" variant="flat" class="mx-4">Analytics</v-btn>

                                <v-list-item-title>{{ item.shortCode }}</v-list-item-title>

                                <v-list-item-subtitle class="mb-1 text-high-emphasis opacity-100">{{ item.shortlink_id }}</v-list-item-subtitle>

                                <v-list-item-subtitle class="text-high-emphasis">{{ item.shortCode }}</v-list-item-subtitle>

                                <Bar :data="item" :options="options" />

                                <template v-slot:append="{ isSelected }">
                                    <v-list-item-action class="flex-column align-end">
                                        <small class="mb-4 text-high-emphasis opacity-60">{{ item.shortCode }}</small>

                                        <v-spacer></v-spacer>

                                        <v-icon v-if="isSelected" color="yellow-darken-3">mdi-star</v-icon>

                                        <v-icon v-else class="opacity-30">mdi-star-outline</v-icon>
                                    </v-list-item-action>
                                </template>
                            </v-list-item>
                        </v-list>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </AppLayout>
</template>
