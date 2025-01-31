<script setup>
import { onMounted, reactive } from 'vue';
import { VForm,  VRow, VCol } from 'vuetify/components';
import LinkDetailsComponent from './LinkDetailsComponent.vue';
import { fetchShortlink, updateLink } from '@/Components/shortlinks/requests';
import MetaDataEditComponent from '@/Components/shortlinks/MetaDataEditComponent.vue';

const props = defineProps({
    auth: Object,
    flash: Object,
    shortlink_id: {
        type: Object,
        required: true,
    },
});

const state = reactive({
    shortlink: {
        id: 0,
        user_url: '',
        is_active: 0,
        metadatas: [],
    },
    redirects: [],
    message: '',
    valid: false,
});

onMounted(async () => {
  try {
    const response = await fetchShortlink(props.shortlink_id.shortlink_id);
    state.shortlink = response.data;
    state.shortlink.metadatas = response.data.metadatas;
  } catch (error) {
    console.error('Error fetching shortlink:', error);
  }
});
</script>

<template>
    <div>
        <v-row>
            <v-col cols="12">
                <h1 class="text-3xl font-semibold">New Link</h1>

                <div v-if="!state.shortlink.id">
                    <p>Create a new short link with optional vanity URL.</p>
                    <p>Add custom parameters, if desired.</p>
                    <p>Create link!</p>
                </div>

                <LinkDetailsComponent
                    v-else
                    :shortlink="state.shortlink"
                    :redirects="state.redirects"
                />

            </v-col>
        </v-row>

        <v-form
            v-model="state.valid"
            @submit.prevent="updateLink(state.shortlink)">

                <MetaDataEditComponent :shortlink="state.shortlink" :message="state.message" />

        </v-form>
    </div>
</template>
