<script setup>
import { ref, computed, onMounted } from 'vue';
import { VBtn, VChip, VRow, VCol, VRadioGroup, VRadio, VCard, VCardItem, VCardActions, VDataTableServer, VTextField, VContainer, VSelect } from 'vuetify/components';

const shortlinks = ref([]);
const radio = ref('All');

const fetchShortlinks = async () => {
    try {
        const response = await axios.get('/api/shortlinks/show/all');
        shortlinks.value = response.data;
    } catch (error) {
        console.error('Error fetching shortlinks:', error);
    }
};

const editShortlink = (shortlink) => {
    window.location.href = `/shortlinks/edit/${shortlink.short_code}`
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

const deleteShortlink = async(shortlink) => {
    const confirmDelete = confirm(`Are you sure you want to delete this shortlink ${shortlink.short_code}?`);
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

const filteredShortlinks = computed(() => {
    if (radio.value === 'All') {
        return shortlinks.value.slice().reverse();
    } else if (radio.value === 'Active') {
        return shortlinks.value.filter(link => link.is_active);
    } else {
        return shortlinks.value.filter(link => !link.is_active);
    }
});

onMounted(fetchShortlinks);
</script>

<template>
        <v-row v-if="shortlinks.length">
            <v-col>
                <h1 class="text-2xl font-bold">{{ shortlinks.length ? "Manage Shortlinks" : "No Shortlinks" }}</h1>
                <div v-if="!shortlinks.length" class="">
                    Create new shortlinks to get started!
                </div>
            </v-col>
            <v-col align="end">
                <v-btn
                    prepend-icon="mdi-plus"
                    color="indigo"
                    class=""
                    @click="addShortlink()">
                    New Shortlink
                </v-btn>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <h2 class="my-2 font-bold">{{ radio }} Shortlinks ({{ filteredShortlinks.length }}/{{ shortlinks.length }})</h2>
            </v-col>

            <v-col>
                <v-select
                    v-model="radio"
                    :items="['All', 'Active', 'Inactive']"
                    label="Filter Shortlinks"
                    ></v-select>
            </v-col>
        </v-row>

        <v-row v-for="shortlink in filteredShortlinks" :key="shortlink.id" justify="center">
            <v-col cols="12" md="12">
                <v-card
                    color="indigo"
                    :variant="shortlink?.is_active ? 'elevated' : 'tonal'"
                    class="mx-auto"
                    :href="`/api/shortlinks/redirect/${shortlink.short_code}`"
                    target="_blank"
                >
                    <v-card-item>
                        <div>
                            <div class="text-overline mb-1">
                                {{ shortlink.short_code }} -
                                {{ new Date(shortlink.updated_at).toLocaleString() }}
                            </div>
                            <p class="my-2">
                                <b>{{ shortlink.is_active ? 'Active' : 'Inactive' }}</b>
                            </p>
                            <a
                                target="_blank"
                                class="text-caption">
                                Redirects to: {{ shortlink.original_url }}
                            </a>

                            <div>
                                <div>clicks: {{ shortlink.total_clicks }}</div>
                                <div>uinque clicks: {{ shortlink.unique_clicks }}</div>
                            </div>
                        </div>
                    </v-card-item>

                    <v-card-actions>
                        <v-btn
                            variant="outlined"
                            :href="`/api/shortlinks/redirect/${shortlink.short_code}`"
                            target="_blank"
                            prepend-icon="mdi-eye"
                            class="m-2">
                            View
                        </v-btn>
                        <v-btn
                            variant="outlined"
                            prepend-icon="mdi-pencil"
                            @click="editShortlink(shortlink)"
                            class="m-2">
                            Edit
                        </v-btn>
                        <v-btn
                            variant="outlined"
                            :prepend-icon="shortlink.is_active ? 'mdi-cancel' : 'mdi-check'"
                            :color="shortlink.is_active ? 'red' : 'green'"
                            @click="toggleActivation(shortlink)"
                            class="m-2">
                            {{ shortlink.is_active ? 'Disable' : 'Enable' }}
                        </v-btn>

                        <v-btn
                            variant="outlined"
                            prepend-icon="mdi-delete"
                            color="red"
                            @click="deleteShortlink(shortlink)"
                            class="m-2">
                            Delete
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
</template>
