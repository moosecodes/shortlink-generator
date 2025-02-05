<script setup>
import { Link } from '@inertiajs/vue3'
import ApplicationMark from '@/Components/ApplicationMark.vue'

import { VBtn, VAppBar, VAppBarTitle } from 'vuetify/lib/components/index.mjs'

import { useTheme } from 'vuetify'

const theme = useTheme()

const toggleTheme = () => {
    theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark'
}
</script>

<template>
    <v-app-bar elevation="2" color="primaryAppBar" dark app>
        <v-app-bar-title>
            <div class="d-flex align-center">
                <Link
                    :href="route('dashboard')"
                    class="d-flex align-center text-decoration-none"
                >
                    <ApplicationMark style="height: 36px" />
                </Link>
                <v-btn variant="plain" @click="toggleTheme"
                    ><small>Toggle Theme</small></v-btn
                >
            </div>
        </v-app-bar-title>

        <nav class="flex justify-between">
            <v-btn variant="text" class="mr-4">Platform</v-btn>

            <v-btn variant="text" class="mr-4">Pricing</v-btn>

            <v-btn
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                variant="elevated"
                class="mr-4"
                color="primary"
            >
                Dashboard
            </v-btn>
            <div v-else>
                <v-btn :href="route('login')" variant="plain" class="mr-4">
                    Login
                </v-btn>
                <v-btn
                    :href="route('register')"
                    color="success"
                    variant="elevated"
                    class="mr-4"
                >
                    Sign up Free
                </v-btn>
            </div>
        </nav>
    </v-app-bar>
</template>
