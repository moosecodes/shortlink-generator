<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';

import {
    VBtn,
    VAppBar,
    VAppBarTitle,
} from 'vuetify/lib/components/index.mjs';

import { useTheme } from 'vuetify';

const theme = useTheme()

const toggleTheme = () => {
  theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark';
}
</script>

<template>
    <v-app-bar elevation="2" color="primary">
        <v-app-bar-title>
            <Link :href="route('dashboard')" class="d-flex align-center text-decoration-none">
                <ApplicationMark class="d-inline-block" style="height: 36px;" />
            </Link>
        </v-app-bar-title>

        <nav class="flex justify-between">
            <v-btn variant="text" class="mr-4">Platform</v-btn>
            <v-btn variant="text" class="mr-4">Solutions</v-btn>
            <v-btn variant="text" class="mr-4">Pricing</v-btn>
            <v-btn variant="text" class="mr-4">Resources</v-btn>
            <v-btn
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                variant="elevated" class="mr-4">
                Dashboard
            </v-btn>
            <div v-else>
                <v-btn
                    :href="route('login')"
                    variant="elevated" class="mr-4">
                    Login
                </v-btn>
                <v-btn
                    :href="route('register')"
                    color="success"
                    variant="flat" class="mr-4">
                    Sign up Free
                </v-btn>
            </div>

            <v-btn @click="toggleTheme">toggle theme</v-btn>
        </nav>
    </v-app-bar>
</template>

