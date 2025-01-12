<script setup>
import { ref, onMounted } from 'vue';
import { VBtn } from 'vuetify/components';

const shortlinks = ref([]);

const fetchShortlinks = async () => {
    try {
        console.log('Fetching shortlinks...');
        const response = await axios.get('/api/shortlinks/show/all');
        shortlinks.value = response.data;
    } catch (error) {
        console.error('Error fetching shortlinks:', error);
    }
};

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

const editShortlink = (shortlink) => {
    window.location.href = `/shortlinks/edit/${shortlink.short_code}`
};

const deleteShortlink = async(shortlink) => {
    const confirmDelete = confirm('Are you sure you want to delete this shortlink?');
    if (confirmDelete) {
        try {
            await axios.delete(`/api/shortlinks/delete/${shortlink.short_code}`);
            window.location.reload();
        } catch (error) {
            console.error('Error deleting shortlink:', error);
        }
    }
}

const toggleActivation = async (shortlink) => {
    try {
        const route = shortlink.is_active
            ? `/api/shortlinks/deactivate/${shortlink.short_code}`
            : `/api/shortlinks/activate/${shortlink.short_code}`;

        await axios.patch(route);

        shortlink.is_active = !shortlink.is_active;
    } catch (error) {
        console.error('Error toggling activation:', error);
    }
};

onMounted(fetchShortlinks);
</script>

<template>
    <div>
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold my-2">{{ shortlinks.length ? "Shortlinks" : "No Shortlinks" }}</h1>

            <div v-if="!shortlinks.length" class="my-2">
                Create new shortlinks to get started!
            </div>

            <v-btn
                color="primary"
                class="my-2"
                @click="addShortlink()">
                New Shortlink
            </v-btn>
        </div>

        <h2 class="my-2 font-bold">{{ shortlinks.filter(link => link.is_active).length }}/{{ shortlinks.length }} Active</h2>
        <div
            v-for="shortlink in shortlinks.filter(link => link.is_active).reverse()"
            :key="shortlink.id"
            class="flex align-center justify-between my-2 p-2 border border-gray-200 rounded-lg">
                <a v-if="shortlink.is_active"
                    :href="`/api/shortlinks/redirect/${shortlink.short_code}`"
                    target="_blank"
                    class="text-green-500">
                    <b>{{ shortlink.short_code }}</b>
                </a>
                <span v-else
                    class="text-red-500">
                    {{ shortlink.short_code }}
                </span>

                <small :class="shortlink.is_active ? 'text-green-500 font-bold' : 'text-red-500 font-bold'">
                    {{ shortlink.is_active ? 'active' : 'disabled' }}
                </small>
                <small>{{ shortlink.total_clicks }}</small>
                <small>{{ shortlink.unique_clicks }}</small>

                <a v-if="shortlink.is_active"
                    :href="`/api/shortlinks/redirect/${shortlink.short_code}`"
                    target="_blank">
                    <b>{{ shortlink.original_url }}</b>
                </a>
                <span v-else>
                    {{ shortlink.original_url }}
                </span>

                <small>{{ `localhost/api/shortlinks/redirect/${shortlink.short_code}` }}</small>

                <v-btn
                    :color="shortlink.is_active ? 'red' : 'green'"
                    @click="toggleActivation(shortlink)"
                    class="m-2">
                    {{ shortlink.is_active ? 'Disable' : 'Activate' }}
                </v-btn>
                <v-btn
                    color="black"
                    @click="editShortlink(shortlink)">
                    Edit
                </v-btn>
                <v-btn
                    color="red"
                    @click="deleteShortlink(shortlink)">
                    X
                </v-btn>
        </div>

    </div>
</template>
