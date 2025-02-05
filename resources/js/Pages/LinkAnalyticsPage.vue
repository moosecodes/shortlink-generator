<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Line, Bar } from 'vue-chartjs'
import { VRow, VCol, VChip, VCard, VCardActions } from 'vuetify/lib/components/index.mjs';
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
import LinkDetailsComponent from '@/Components/shortlinks/LinkDetailsComponent.vue';

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
    shortlink: Object,
});

const lineOptions = {
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
</script>

<template>
    <AppLayout title="Link Analytics">
        <v-row>
            <v-col cols="12" md="12">
                <p class="text-3xl font-semibold">Link Analytics</p>
            </v-col>
        </v-row>

        <v-row v-if="props?.graphs?.length">
            <v-col cols="12" md="6">
                <p class="text-3xl font-semibold mb-4">Shortlink -> {{ shortlink.short_code }} </p>
                <LinkDetailsComponent v-if="props.shortlink" :filteredShortlinks="[props.shortlink]" />
            </v-col>

            <v-col cols="12" md="6">
                <p class="text-3xl font-semibold mb-4">Daily Clicks</p>

                <v-col v-for="(graph, i) in Array.from(props.graphs)" :key="i" col="12" md="12" >
                    <Line :data="graph" :options="lineOptions" class="my-4" />
                </v-col>

                <v-col v-for="(graph, i) in Array.from(props.graphs)" :key="i" col="12" md="12" >
                    <Bar :data="graph" :options="barOptions" class="my-4" />
                </v-col>
            </v-col>

            <v-col v-if="props?.locations?.length" cols="12" md="12">
                <v-card class="my-2">
                    <WorldTrafficComponent :locations="deDupedLocations()" />

                    <div v-for="(location, i) in deDupedLocations(props.locations)" :key="i" class="m-2">
                        <p class="font-weight-bold">{{ location.country_name }}</p>
                        <p> {{ location.timezone }}</p>
                    </div>
                </v-card>

            </v-col>
        </v-row>
    </AppLayout>
</template>
