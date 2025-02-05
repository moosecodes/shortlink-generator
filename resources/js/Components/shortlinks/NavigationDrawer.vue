<script setup>
import { router } from '@inertiajs/vue3'
import { onMounted, reactive, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import {
    VBtn,
    VDivider,
    VList,
    VListItem,
    VNavigationDrawer,
    VAppBarNavIcon,
} from 'vuetify/lib/components/index.mjs'

const props = defineProps({
    auth: Object,
})

const state = reactive({
    drawer: true,
    rail: false,
})

const navigateTo = (routeName) => {
    router.get(route(routeName))
}

onMounted(() => {
    console.log(state)
})
</script>

<template>
    <v-navigation-drawer
        v-model="state.drawer"
        :rail="!state.rail"
        permanent
        elevation="2"
    >
        <v-list>
            <v-list-item
                @click="state.rail = !state?.rail"
                :prepend-avatar="$page.props.auth.user.profile_photo_url"
                :title="$page.props.auth.user.name"
                :subtitle="$page.props.auth.user.email"
            >
            </v-list-item>
        </v-list>

        <div v-if="!!state.rail" class="flex justify-center p-4">
            <v-btn
                prepend-icon="mdi-plus"
                type="submit"
                color="primary"
                @click="navigateTo('link.create')"
                class="flex items-center justify-center"
                block
            >
                Create Link
            </v-btn>
        </div>

        <v-list>
            <v-list-item
                link
                @click="navigateTo('dashboard')"
                :active="route().current('dashboard')"
                prepend-icon="mdi-view-dashboard"
                title="Dashboard"
            />

            <v-list-item
                link
                @click="navigateTo('show.links')"
                :active="route().current('show.links')"
                prepend-icon="mdi-link-variant"
                title="Manage Links"
            />

            <v-list-item
                disabled
                link
                @click="navigateTo('qr-codes')"
                :active="route().current('qr-codes')"
                prepend-icon="mdi-qrcode-scan"
                title="QR Codes"
            />

            <v-list-item
                disabled
                link
                @click="navigateTo('pages')"
                :active="route().current('pages')"
                prepend-icon="mdi-file-document-outline"
                title="Pages"
            />

            <v-list-item
                disabled
                link
                @click="navigateTo('page.analytics')"
                :active="route().current('page.analytics')"
                prepend-icon="mdi-google-analytics"
                title="Analytics"
            />

            <v-list-item
                disabled
                link
                @click="navigateTo('campaigns')"
                :active="route().current('campaigns')"
                prepend-icon="mdi-folder-outline"
                title="Campaigns"
            />

            <v-list-item
                disabled
                link
                @click="navigateTo('custom-domains')"
                :active="route().current('custom-domains')"
                prepend-icon="mdi-folder-outline"
                title="Custom Domains"
            />

            <v-divider />

            <v-list-item
                disabled
                prepend-icon="mdi-cog"
                title="Settings"
                @click="navigateTo('settings')"
                :active="route().current('settings')"
            />
        </v-list>
    </v-navigation-drawer>
</template>
