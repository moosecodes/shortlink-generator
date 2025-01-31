<template>
    <div class="link-chart">
        <h2>Link Chart</h2>
        <canvas id="LinkChartComponentCanvas" />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { Chart } from 'chart.js';

export default {
    name: 'LinkChartComponent',
    setup() {
        const chart = ref(null);

        onMounted(() => {
            const ctx = document.getElementById('LinkChartComponentCanvas').getContext('2d');
            chart.value = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [{
                        label: 'Link Clicks',
                        data: [65, 59, 80, 81, 56, 55, 40],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        return {
            chart
        };
    }
};
</script>

<style scoped>
.link-chart {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
}

canvas {
    width: 100%;
    height: 400px;
}
</style>
