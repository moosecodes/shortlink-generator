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
    userId: page.props.auth.user?.id,
    shortlink: {
        custom_short_code: '',
        user_url: 'https://www.google.com',
        metadatas: [
            { meta_key: 'utm_source', meta_value: 'milky way' },
            { meta_key: 'utm_medium', meta_value: 'email' },
            { meta_key: 'utm_campaign', meta_value: 'newsletter' },
            { meta_key: 'utm_term', meta_value: 'spring_sale' },
            { meta_key: 'utm_content', meta_value: 'cta_button' },
        ],
    },
    showFormFields: true,
    message: '',
    valid: false,
});

const message = ref('');
const valid = ref(false);

const submitForm = async () => {
    try {
        const response = await axios.post('/api/manage/new', {
            userId: state.userId,
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
  const parameters = state.shortlink.metadatas.map(
    m => m.meta_key
        ? `${m.meta_key}=${m.meta_value}`
        : null).join('&');
  return parameters;
});
</script>

<template>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold my-2">New Link</h1>
    </div>

    <v-form v-model="valid" @submit.prevent="submitForm">
        <v-row>
            <v-col>
                <v-btn type="submit" color="primary" @click="navigateTo('show.links')">Create Shortlink</v-btn>
            </v-col>
            <v-col>
                <p v-if="message">{{ message }}</p>
            </v-col>
        </v-row>
        <v-row v-if="state.shortlink.user_url.length">
            <v-col cols="12">Link Preview:</v-col>
            <v-col cols="12">
                {{ state.shortlink.user_url }}
                /
                {{ state.shortlink.custom_short_code || "XxXxXxXx"}}
                ?
                {{ showParameterPreview }}
            </v-col>
        </v-row>
        <v-row>
            <v-col>Target Link</v-col>
            <v-col cols="12" md="12">
                <v-text-field
                    v-model="state.shortlink.user_url"
                    label="Redirect to (URL)"
                    required
                />
            </v-col>

            <v-col>Custom Link (optional)</v-col>
            <v-col cols="12" md="12">
                <v-text-field
                    v-model="state.shortlink.custom_short_code"
                    label="Enter custom back-half of the URL"
                />
            </v-col>
        </v-row>

        <v-row class="my-4" >
            <v-col>
                <v-btn color="info" @click="toggleUTMFields">{{ state.showFormFields ? 'Hide' : 'Show' }} Form Fields</v-btn>
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
            <v-col>UTM Fields</v-col>
            <v-col v-for="(field, i) in state.shortlink.metadatas.filter(data => data.meta_key.startsWith('utm_'))" :key="i" cols="12" md="12">
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


    </v-form>
</template>
