<script setup>
import { GoogleMap, AdvancedMarker } from 'vue3-google-map'
import { VCol, VRow } from 'vuetify/lib/components/index.mjs'

const props = defineProps(['locations'])

const pinOptions = { background: '#f87979' }

const deDupedLocations = (locations) => {
    if (!locations) {
        return []
    }
    return locations.filter(
        (location, index, self) =>
            index ===
            self.findIndex((t) => t.country_name === location.country_name),
    )
}

const googleMapsApiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY
</script>

<template>
    <v-row>
        <v-col cols="12" md="12">
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
                            lng: parseFloat(location.longitude),
                        },
                    }"
                />
            </GoogleMap>
        </v-col>
    </v-row>
</template>
