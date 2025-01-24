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
});

const options = {
    responsive: false,
    maintainAspectRatio: true,
};
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <div  v-for="(graph, i) in Array.from(props.graphs).reverse()" :key="i">
                <p class="my-4">Line Graph Clicks Over Time for: {{ graph.shortCode }}</p>
                <Line
                    :data="graph"
                    :options="options"
                    class="my-4"
                />
            </div>

        </div>
    </AppLayout>
</template>
