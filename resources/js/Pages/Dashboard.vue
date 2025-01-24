<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { GoogleMap, Marker, AdvancedMarker } from 'vue3-google-map'
import { VRow, VCol, VExpansionPanels, VExpansionPanel } from 'vuetify/lib/components/index.mjs';
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
            }
        },
        y: {
            display: true,
            title: {
                display: true,
                text: 'Clicks'
            }
        }
    },
    plugins: {
        legend: {
            display: false
        },
    },
};

const deDupedLocations = () => {
    return props.locations.filter((location, index, self) =>
        index === self.findIndex((t) => (
            t.country_name === location.country_name
        ))
    );
};

const custom = [
    {
        "elementType": "geometry",
        "stylers": [
        {
            "color": "#1d2c4d"
        }
        ]
    },
    {
        "elementType": "labels.text.fill",
        "stylers": [
        {
            "color": "#8ec3b9"
        }
        ]
    },
    {
        "elementType": "labels.text.stroke",
        "stylers": [
        {
            "color": "#1a3646"
        }
        ]
    },
    {
        "featureType": "administrative.country",
        "elementType": "geometry.stroke",
        "stylers": [
        {
            "color": "#4b6878"
        }
        ]
    },
    {
        "featureType": "administrative.land_parcel",
        "elementType": "labels.text.fill",
        "stylers": [
        {
            "color": "#64779e"
        }
        ]
    },
    {
        "featureType": "administrative.province",
        "elementType": "geometry.stroke",
        "stylers": [
        {
            "color": "#4b6878"
        }
        ]
    },
    {
        "featureType": "landscape.man_made",
        "elementType": "geometry.stroke",
        "stylers": [
        {
            "color": "#334e87"
        }
        ]
    },
    {
        "featureType": "landscape.natural",
        "elementType": "geometry",
        "stylers": [
        {
            "color": "#023e58"
        }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
        {
            "color": "#283d6a"
        }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "labels.text.fill",
        "stylers": [
        {
            "color": "#6f9ba5"
        }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "labels.text.stroke",
        "stylers": [
        {
            "color": "#1d2c4d"
        }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry.fill",
        "stylers": [
        {
            "color": "#023e58"
        }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "labels.text.fill",
        "stylers": [
        {
            "color": "#3C7680"
        }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
        {
            "color": "#304a7d"
        }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.fill",
        "stylers": [
        {
            "color": "#98a5be"
        }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.stroke",
        "stylers": [
        {
            "color": "#1d2c4d"
        }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry",
        "stylers": [
        {
            "color": "#2c6675"
        }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
        {
            "color": "#255763"
        }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "labels.text.fill",
        "stylers": [
        {
            "color": "#b0d5ce"
        }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "labels.text.stroke",
        "stylers": [
        {
            "color": "#023e58"
        }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "labels.text.fill",
        "stylers": [
        {
            "color": "#98a5be"
        }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "labels.text.stroke",
        "stylers": [
        {
            "color": "#1d2c4d"
        }
        ]
    },
    {
        "featureType": "transit.line",
        "elementType": "geometry.fill",
        "stylers": [
        {
            "color": "#283d6a"
        }
        ]
    },
    {
        "featureType": "transit.station",
        "elementType": "geometry",
        "stylers": [
        {
            "color": "#3a4762"
        }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
        {
            "color": "#0e1626"
        }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.fill",
        "stylers": [
        {
            "color": "#4e6d70"
        }
        ]
    }
];

const position = {
  lat: parseFloat(location.latitude),
  lng: parseFloat(location.longitude)
};
const googleMapsApiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
</script>

<template>
    <AppLayout title="Dashboard">
        <v-row>
            <v-col cols="12" md="12">
                <p class="text-2xl font-semibold">Link Analytics Dashboard</p>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12" md="12">
                <p class="text-2xl font-semibold">Clicks Over Time</p>
            </v-col>
            <v-col col="12" md="4" v-for="(graph, i) in Array.from(props.graphs)" :key="i">
                <p class="my-2">Short Code: {{ graph.shortCode }}</p>
                <p class="mb-4">Total Clicks: {{ graph.datasets[0].data.reduce((accumulator, currentValue) => accumulator + currentValue, 0) }}</p>
                <Line
                    :data="graph"
                    :options="options"
                    class="my-4"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12" md="12">
                <div class="mt-4 mb-2">Stats</div>
                <v-expansion-panels>
                    <v-expansion-panel
                        v-for="(graph, i) in Array.from(props.graphs)" :key="i"
                        :title="graph.shortCode"
                        :text="graph.datasets[0].data.reduce((accumulator, currentValue) => accumulator + currentValue, 0).toString()">
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12" md="12">
                <div class="mt-4 mb-2">Locations</div>
            <GoogleMap
                    :api-key="googleMapsApiKey"
                    style="width: 100%; height: 400px"
                    :styles="custom"
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
                ></v-expansion-panel>
            </v-expansion-panels>
            </v-col>

        </v-row>

        <v-row>
            <v-col cols="12" md="12">
                <div class="mt-4 mb-2">Top Referrers</div>
                <v-expansion-panels>
                    <v-expansion-panel
                        v-for="i in 3"
                        :key="i"
                        text="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."
                        title="Item"
                    ></v-expansion-panel>
                </v-expansion-panels>
            </v-col>
        </v-row>


    </AppLayout>
</template>
