<script setup>
import { GoogleMap, AdvancedMarker } from 'vue3-google-map'

defineProps(['googleMapsApiKey', 'locations']);

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
</script>

<template>
    <div>
        <div class="my-2 text-2xl font-semibold">Locations</div>

        <p v-for="(location, i) in deDupedLocations(locations)" :key="i" class="my-2">
            {{ location.country_name + ' - ' + location.timezone }}
        </p>

        <GoogleMap
            style="width: 100%; height: 400px"
            :api-key="googleMapsApiKey"
            mapId="DEMO_MAP_ID"
            :disableDefaultUi="true"
            :zoom="1"
        >
            <AdvancedMarker
                v-for="(location, i) in deDupedLocations(locations)" :key="i"
                :pin-options="pinOptions"
                :options="{
                    position: {
                        lat: parseFloat(location.latitude),
                        lng: parseFloat(location.longitude)
                }}"
            />
        </GoogleMap>
    </div>

</template>
