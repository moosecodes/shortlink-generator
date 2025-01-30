<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

import {
    VBtn,
    VDivider,
    VList,
    VListItem,
    VNavigationDrawer,
} from 'vuetify/lib/components/index.mjs';

defineProps({
    auth: Object,
});

const drawer = ref(true);
const rail = ref(false);

const navigateTo = (routeName) => {
    router.get(route(routeName));
};
</script>

<template>
<!-- Navigation Drawer -->
<v-navigation-drawer
            v-model="drawer"
            :rail="rail"
            permanent
            elevation="2"
        >
            <v-divider />

            <v-btn @click="rail = !rail">Rail</v-btn>

            <v-divider />

            <v-list>
                <v-list-item
                    :prepend-avatar="$page.props.auth.user.profile_photo_url"
                    :title="$page.props.auth.user.name"
                    :subtitle="$page.props.auth.user.email"
                    :href="route('profile.show')"
                >
                </v-list-item>
            </v-list>

            <v-divider />

            <div v-if="!rail" class="flex justify-center p-4">
                <v-btn
                    prepend-icon="mdi-plus"
                    type="submit"
                    color="primary"
                    @click="navigateTo('link.create')"
                    class="flex justify-center items-center"
                    block>
                Create Link
                </v-btn>
            </div>

            <v-divider />

            <v-list>
                <v-list-item
                    link
                    @click="navigateTo('dashboard')"
                    :active="route().current('dashboard')"
                    prepend-icon="mdi-view-dashboard"
                    title="Dashboard"
                    value="dashboard"
                />

                <v-list-item
                    link
                    @click="navigateTo('show.links')"
                    :active="route().current('show.links')"
                    prepend-icon="mdi-link-variant"
                    title="Manage Links"
                />

                <v-list-item
                    link
                    @click="navigateTo('qr-codes')"
                    :active="route().current('qr-codes')"
                    prepend-icon="mdi-qrcode-scan"
                    title="QR Codes"
                />

                <!-- <v-list-item
                    link
                    @click="navigateTo('dashboard')"
                    :active="route().current('dashboard')"
                    prepend-icon="mdi-file-document-outline"
                    title="Pages"
                /> -->

                <!-- <v-list-item
                    link
                    @click="navigateTo('page.analytics')"
                    :active="route().current('page.analytics')"
                    prepend-icon="mdi-google-analytics"
                    title="Analytics"
                /> -->

                <!-- <v-list-item
                    link
                    @click="navigateTo('dashboard')"
                    :active="route().current('dashboard')"
                    prepend-icon="mdi-folder-outline"
                    title="Campaigns"
                /> -->

                <!-- <v-list-item
                    link
                    @click="navigateTo('custom-domains')"
                    :active="route().current('custom-domains')"
                    prepend-icon="mdi-folder-outline"
                    title="Custom Domains"
                /> -->

                <v-divider />

                <v-list-item
                    prepend-icon="mdi-cog"
                    title="Settings"
                    @click="navigateTo('settings')"
                    :active="route().current('settings')"
                />
            </v-list>
        </v-navigation-drawer>
</template>
