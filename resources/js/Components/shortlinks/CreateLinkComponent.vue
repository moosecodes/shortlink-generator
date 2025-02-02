<script setup>
import { ref, reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { VForm, VRow, VCol, VTextField, VBtn } from 'vuetify/components';
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
    auth: Object,
    flash: Object,
});

const state = reactive({
    shortlink: {
        custom_short_code: '',
        user_url: 'https://www.google.com',
        metadatas: [
            { meta_key: 'utm_source', meta_value: '' },
            { meta_key: 'utm_medium', meta_value: '' },
            { meta_key: 'utm_campaign', meta_value: '' },
            { meta_key: 'utm_term', meta_value: '' },
            { meta_key: 'utm_content', meta_value: '' },
        ],
    },
    showFormFields: true,
    message: '',
    valid: false,
});

const message = ref('');
const valid = ref(false);

const createNewLink = async () => {
    try {
        const response = await axios.post('/api/manage/new', {
            user_url: state.shortlink.user_url,
            metadatas: state.shortlink.metadatas,
            custom_short_code: state.shortlink.custom_short_code,
        });

        state.message = response;
    } catch (error) {
        console.error('Error creating shortlink:', error);
        state.message = error;
    }
};

const toggleUTMFields = () => {
    state.showFormFields = !state.showFormFields;
}

const addNewField = () => {
    state.shortlink.metadatas.push({ meta_key: '', meta_value: '' });
};

const navigateTo = (routeName) => {
    router.get(route(routeName));
};

const showParameterPreview = computed(() => {
  let parameters = state.shortlink.metadatas.map(
    m => (m.meta_key && m.meta_value)
        ? `${m.meta_key}=${m.meta_value}`
        : null);
    parameters = parameters.filter(p => p);
  return parameters.join(' & ');
});
</script>

<template>
    <v-row>
        <v-col cols="12">
            <h1 class="text-3xl font-semibold">New Link</h1>
            <p>Create a new short link with optional vanity URL.</p>
            <p>Add custom parameters, if desired.</p>
            <p>Create link!</p>
        </v-col>
    </v-row>

    <v-form v-model="valid" @submit.prevent="createNewLink">
        <v-row>
            <v-col>Target URL</v-col>
            <v-col cols="12" md="12">
                <v-text-field
                    v-model="state.shortlink.user_url"
                    label="Enter target link here"
                    required
                />
            </v-col>

            <v-col>Customize Link <small>(optional)</small></v-col>
            <v-col cols="12" md="12">
                <v-text-field
                    v-model="state.shortlink.custom_short_code"
                    label="Enter custom back-half of the URL"
                />
            </v-col>
        </v-row>

        <v-row v-if="state.shortlink.user_url.length">
            <v-col cols="12">
            <p cols="12">Link Preview</p>
                {{ state.shortlink.user_url }}
                /
                {{ state.shortlink?.custom_short_code || "XxXxXxXx"}}
                ?
                {{ showParameterPreview }}
            </v-col>
        </v-row>

        <v-row class="my-4" >
            <v-col>
                <v-btn
                    color="info"
                    @click="toggleUTMFields">
                        {{ state.showFormFields ? 'Hide' : 'Show' }} UTM Fields
                    </v-btn>
            </v-col>
        </v-row>

        <v-row v-if="state.showFormFields">
            <v-col cols="12" md="12">UTM Fields</v-col>
            <v-col v-for="(field, i) in state.shortlink.metadatas.filter(data => data.meta_key.startsWith('utm_'))" :key="i" cols="12" md="4">
                <v-text-field
                    v-model="field.meta_key"
                    label="Key"
                    :label="field.meta_key"
                />
                <v-text-field
                    v-model="field.meta_value"
                    label="Value"
                    :label="field.meta_value"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col>Custom Fields</v-col>
            <v-col v-for="(field, i) in state.shortlink.metadatas.filter(data => !data.meta_key.startsWith('utm_'))" :key="i" cols="12" md="12">
                <v-text-field
                    v-model="field.meta_key"
                    label="Key"
                    :label="field.meta_key"
                />
                <v-text-field
                    v-model="field.meta_value"
                    label="Value"
                    :label="field.meta_value"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-btn color="secondary" @click="addNewField">Add Custom Field</v-btn>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-btn type="submit" color="primary" @click="navigateTo('show.links')">Create Shortlink</v-btn>
            </v-col>
            <v-col>
                <p v-if="message">{{ message }}</p>
            </v-col>
        </v-row>
    </v-form>
</template>
