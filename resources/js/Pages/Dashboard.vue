<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

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
    responsive: false,
    maintainAspectRatio: true,
};

const deDupedLocations = () => {
    console.log(props.locations);

    return props.locations.filter((location, index, self) =>
        index === self.findIndex((t) => (
            t.country_name === location.country_name
        ))
    );
};
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <div class="my-8">
                <p class="text-4xl font-semibold my-4">Click Location Data</p>
                <div v-for="(location, i) in deDupedLocations()" :key="i" class="my-4">
                    <p v-if="location.city_name" class="text-1xl font-semibold">{{ location.city_name }}</p>
                    <p v-if="location.country_name" class="text-1xl font-semibold">{{ location.country_name }}</p>
                    <p v-if="location.timezone">{{ location.timezone }}</p>

                    <p v-if="location.postal_code">{{ location.postal_code }}</p>
                    <p v-if="location.latitude">{{ location.latitude }}, {{ location.longitude }}</p>
                    <p></p>
                </div>
            </div>

            <div class="my-8">
                <p class="text-4xl font-semibold">Click Graph Data</p>
                <div v-for="(graph, i) in Array.from(props.graphs).reverse()" :key="i">
                    <p class="my-4">Line Graph Clicks Over Time for: {{ graph.shortCode }}</p>
                    <Line
                        :data="graph"
                        :options="options"
                        class="my-4"
                    />
                </div>
            </div>


        </div>
    </AppLayout>
</template>
