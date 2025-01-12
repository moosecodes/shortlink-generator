<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ListAllShortlinks from '@/Components/shortlinks/ListAllShortlinks.vue';

const addShortlink = async () => {
    const originalUrl = prompt('Please enter the original URL:', 'https://example.com');
    if (originalUrl) {
        try {
            const response = await axios.post('/api/shortlinks', { original_url: originalUrl });
            window.location.reload();
        } catch (error) {
            console.error('Error adding shortlink:', error);
        }
    }
};
</script>

<template>
    <AppLayout title="View All Shortlinks">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                View All Shortlinks">
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <ListAllShortlinks />
        </div>
    </AppLayout>
</template>
