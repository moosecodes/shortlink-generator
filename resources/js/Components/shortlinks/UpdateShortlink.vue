<script setup>
import { onMounted, ref } from 'vue';
import { VForm, VRow, VCol, VTextField, VBtn } from 'vuetify/components';

const originalUrl = ref('');
const customFields = ref([]);
const isActive = ref(0);
const message = ref('');
const valid = ref(false);
const shortlink = ref({});
const showUTMFields = ref(true);
const utmSource = ref('');
const utmMedium = ref('');
const utmCampaign = ref('');
const utmTerm = ref('');
const utmContent = ref('');

const fetchShortlink = async () => {
    try {
        const response = await axios.get(`/api/shortlinks/show/40a1ac47`);
        shortlink.value = response.data;
        originalUrl.value = shortlink.value.original_url;
    } catch (error) {
        console.error('Error fetching shortlink:', error);
    }
};

const submitForm = async () => {
    try {
        const response = await axios.patch('/api/shortlinks/update', data);
        message.value = 'Shortlink created successfully!';
    } catch (error) {
        console.error('Error creating shortlink:', error);
        message.value = 'Error creating shortlink.';
    }
};

const toggleUTMFields = () => {
    showUTMFields.value = !showUTMFields.value;
}
const addNewField = () => {
    customFields.value.push({ key: '', value: '' });
};

onMounted(fetchShortlink)
</script>

<template>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold my-2">Edit Shortlink</h1>
    </div>
{{ shortlink }}
    <v-form v-model="valid" @submit.prevent="submitForm">
        <v-row>
            <v-col>
                <v-btn type="submit" color="primary">Update Shortlink</v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-text-field
                v-model="originalUrl"
                label="Original URL"
                disabled>
            </v-text-field>
        </v-row>
        <v-row>
            <v-col>
                <v-btn color="secondary" @click="toggleUTMFields">{{ showUTMFields ? 'Hide' : 'Show' }} UTM Fields</v-btn>
            </v-col>
        </v-row>
        <v-row v-if="showUTMFields">
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmSource"
                    label="UTM Source"
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmMedium"
                    label="UTM Medium"
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmCampaign"
                    label="UTM Campaign"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row v-if="showUTMFields">
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmTerm"
                    label="UTM Term"
                ></v-text-field>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="utmContent"
                    label="UTM Content"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-btn color="secondary" @click="addNewField">Add Custom Field</v-btn>
            </v-col>
        </v-row>
        <v-row >
            <v-col v-for="(field, index) in customFields" :key="index" cols="12" md="6">
                <v-text-field
                    v-model="field.key"
                    :label="`Custom Key ${index + 1}`"
                    required
                ></v-text-field>
                <v-text-field
                    v-model="field.value"
                    :label="`Custom Value ${index + 1}`"
                    required
                ></v-text-field>
            </v-col>
        </v-row>
    </v-form>
    <p v-if="message">{{ message }}</p>
</template>
