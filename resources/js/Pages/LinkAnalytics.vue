<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { GoogleMap, Marker, AdvancedMarker } from 'vue3-google-map'
import { VBtn, VRow, VCol, VExpansionPanels, VExpansionPanel } from 'vuetify/lib/components/index.mjs';
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

const custom2 = [
    {
        "featureType": "all",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "saturation": 36
            },
            {
                "color": "#000000"
            },
            {
                "lightness": 40
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#000000"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            },
            {
                "weight": 1.2
            }
        ]
    },
    {
        "featureType": "administrative.country",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#fe2c55"
            }
        ]
    },
    {
        "featureType": "administrative.locality",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#c4c4c4"
            }
        ]
    },
    {
        "featureType": "administrative.neighborhood",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#fe2c55"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 21
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "poi.business",
        "elementType": "geometry",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#fe2c55"
            },
            {
                "lightness": "0"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "color": "#fe2c55"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 18
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#575757"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "color": "#2c2c2c"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#999999"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 19
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            }
        ]
    }
];

const googleMapsApiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
</script>

<template>
    <AppLayout title="Dashboard">
        <v-row>
            <v-col cols="12" md="12">
                <p class="text-2xl font-semibold">Link Analytics Dashboard</p>
            </v-col>
        </v-row>
{{ props.graphs }}
        <div v-if="props?.graphs?.length">
            <v-row>
                <v-col cols="12" md="12">
                    <p class="text-2xl font-semibold">Clicks Over Time</p>
                    <p class="mb-4">Short Codes</p>
                </v-col>
                <v-col col="12" md="4" v-for="(graph, i) in Array.from(props.graphs)" :key="i">
                    <v-btn variant="plain">{{ graph.shortCode }}</v-btn>
                    <Line
                        :data="graph"
                        :options="options"
                        class="my-4"
                    />
                </v-col>
            </v-row>

            <v-row v-if="props?.locations?.length">
                <v-col cols="12" md="12">
                    <div class="mt-4 mb-2">Locations</div>
                <GoogleMap
                        :api-key="googleMapsApiKey"
                        style="width: 100%; height: 400px"
                        :styles="custom2"
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
        </div>
    </AppLayout>
</template>
