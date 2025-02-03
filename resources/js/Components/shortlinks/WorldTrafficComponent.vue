<script setup>
import { GoogleMap, AdvancedMarker } from 'vue3-google-map'
import { VCol, VRow, VExpansionPanels, VExpansionPanel } from 'vuetify/lib/components/index.mjs';

const props = defineProps(['locations']);

const pinOptions = { background: '#f87979' };

const deDupedLocations = (locations) => {
    if (!locations) {
        return [];
    }
    return locations.filter((location, index, self) =>
        index === self.findIndex((t) => (
            t.country_name === location.country_name
        ))
    );
};

const googleMapsApiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
</script>

<template>
    <v-row class="my-2 text-3xl font-semibold">
        <v-col cols="12" md="12">Locations</v-col>
    </v-row>

    <v-row>
        <v-col cols="12" md="6">
            <GoogleMap
                style="width: 100%; height: 400px"
                :api-key="googleMapsApiKey"
                mapId="DEMO_MAP_ID"
                :disableDefaultUi="true"
                :zoom="1"
            >
                <AdvancedMarker
                    v-for="(location, i) in deDupedLocations(locations)"
                    :key="i"
                    :pin-options="pinOptions"
                    :options="{
                        position: {
                            lat: parseFloat(location.latitude),
                            lng: parseFloat(location.longitude)
                    }}"
                />
            </GoogleMap>
        </v-col>

        <v-col cols="12" md="6">
            <v-expansion-panels>
                <v-expansion-panel
                    v-for="(location, i) in deDupedLocations(props.locations)"
                    :key="i"
                    :title="location.country_name"
                    :text="`Timezone: ${location.timezone}`"
                />
            </v-expansion-panels>
        </v-col>
    </v-row>
</template>
