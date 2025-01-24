<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { GoogleMap, Marker, AdvancedMarker } from 'vue3-google-map'
import { VRow, VCol } from 'vuetify/lib/components/index.mjs';
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
};

const deDupedLocations = () => {
    return props.locations.filter((location, index, self) =>
        index === self.findIndex((t) => (
            t.country_name === location.country_name
        ))
    );
};

</script>

<template>
    <AppLayout title="Dashboard">
        <!-- <v-row>
            <v-col>
                <GoogleMap
                    api-key="AIzaSyCz78riXILDivfsMbaQEOVACEZJjjuGoRU"
                    style="width: '100%'; height: 400px"
                    :zoom="3"
                >
                    <Marker v-for="(location, i) in deDupedLocations()" :key="i" :options="{ position: { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) } }" />
                </GoogleMap>
            </v-col>
        </v-row> -->
        <v-row class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <v-col cols="12" md="4">
                <p class="text-4xl font-semibold mb-4">Click Locations</p>
                <div v-for="(location, i) in deDupedLocations()" :key="i" class="my-4">
                    <p v-if="location.city_name" class="text-1xl font-semibold">{{ location.city_name }}</p>
                    <p v-if="location.country_name" class="text-1xl font-semibold">{{ location.country_name }}</p>
                    <p v-if="location.timezone">{{ location.timezone }}</p>

                    <!-- <p v-if="location.postal_code">{{ location.postal_code }}</p>
                    <p v-if="location.latitude">{{ location.latitude }}, {{ location.longitude }}</p> -->

                    <GoogleMap
                        api-key="AIzaSyCz78riXILDivfsMbaQEOVACEZJjjuGoRU"
                        style="width: 100%; height: 200px"
                        :center="{ lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) }"
                        :zoom="2"
                    >
                        <Marker :options="{ position: { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) } }" />
                    </GoogleMap>
                </div>
            </v-col>

            <v-col>
                <p class="text-4xl font-semibold">Click Graph Data</p>
                <div v-for="(graph, i) in Array.from(props.graphs).reverse()" :key="i">
                    <p class="my-4">Clicks Over Time -> {{ graph.shortCode }}</p>
                    <Line
                        :data="graph"
                        :options="options"
                        class="my-4"
                    />
                </div>
            </v-col>


        </v-row>
    </AppLayout>
</template>
