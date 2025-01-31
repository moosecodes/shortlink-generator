<script setup>
import { router } from '@inertiajs/vue3';
import { VBtn, VChip, VCard, VRow, VCol, VTextField, VDivider } from 'vuetify/components';
import { computed, onMounted, reactive } from 'vue';

const props = defineProps({
    shortlink: Object,
});

const state = reactive({
    shortlink: {
        id: Number,
        user_url: String,
        is_active: Boolean,
        metadatas: [],
    },
    message: undefined,
    valid: false,
});

const addCustomParam = () => {
    state.shortlink.metadatas.push({ meta_key: '', meta_value: '' });
    console.log(state.shortlink);
};

const navigateTo = (routeName) => {
    router.get(route(routeName));
};

const sortedMetadatas = computed(() => {
    if (!state.shortlink.metadatas) {
        return [];
    }
    return state.shortlink.metadatas.slice().sort((a, b) => {
        const aHasUtm = a.meta_key.startsWith('utm_');
        const bHasUtm = b.meta_key.startsWith('utm_');
        if (aHasUtm && !bHasUtm) return -1;
        if (!aHasUtm && bHasUtm) return 1;
        return 0;
    });
});

onMounted(() => {
    state.shortlink.id = props.shortlink.id
    state.shortlink.user_url = props.shortlink.user_url
    state.shortlink.is_active = props.shortlink.is_active
    state.shortlink.metadatas = props.shortlink.metadatas
    state.message = undefined
    state.valid = false

    console.log('MetaDataEditComponent.vue mounted', state.shortlink);

});
</script>

<template>
    <div>
        <v-row>
            <v-col cols="12" md="12">
                <v-card>
                    <v-btn
                        type="submit"
                        color="primary"
                        @click="navigateTo('dashboard')"
                        class="m-4"
                    >{{state.message ? state.message : 'Update Shortlink' }}
                    </v-btn>
                </v-card>
            </v-col>

            <!-- Custom Parameters -->
            <v-col cols="12" md="12">
                <p><b>Custom Parameters</b></p>
            </v-col>

            <v-col v-for="(field, i) in sortedMetadatas.filter(d => !d.meta_key.includes('utm_'))" :key="i" cols="12" md="4">
                <div class="mb-4"><b>Custom Parameter {{ i + 1 }}</b></div>
                <v-text-field
                    v-model="field.meta_key"
                    variant="outlined"
                    label="Key"
                    required
                    :clearable="true"
                />
                <v-text-field
                    v-model="field.meta_value"
                    variant="outlined"
                    label="Value"
                    required
                />
            </v-col>

            <v-col cols="12" md="12">
                <v-card>
                    <v-btn
                        color="secondary"
                        @click="addCustomParam"
                        class="m-4"
                    >Add Custom Parameter</v-btn>
                </v-card>
            </v-col>
        </v-row>

        <!-- UTM Parameters -->
        <v-row>
            <v-col cols="12" md="12"><b>UTM Parameters</b></v-col>

            <v-col v-for="(field, i) in sortedMetadatas.filter(d => d.meta_key.includes('utm_'))" :key="i" cols="12" md="4">
                <v-chip class="mb-4">{{ field.meta_key }}</v-chip>

                <v-text-field
                    v-model="field.meta_value"
                    label="Value"
                    required
                />
            </v-col>
        </v-row>
    </div>

</template>
