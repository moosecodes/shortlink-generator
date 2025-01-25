<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import {
    VApp,
    VAppBar,
    VAppBarNavIcon,
    VAppBarTitle,
    VAvatar,
    VBtn,
    VContainer,
    VDivider,
    VFooter,
    VIcon,
    VImg,
    VList,
    VListItem,
    VListItemTitle,
    VListSubheader,
    VMain,
    VMenu,
    VNavigationDrawer,
    VSpacer,
} from 'vuetify/lib/components/index.mjs';

import { useTheme } from 'vuetify';

defineProps({
    title: String,
});

const theme = useTheme()

const toggleTheme = () => {
  theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark';
}

const drawer = ref(true);
const rail = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'),
     {
        team_id: team.id,

    },
     {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};

const navigateTo = (routeName) => {
    router.get(route(routeName));
};
</script>

<template>
    <v-app>
        <Head :title="title" />

        <Banner />

        <!-- Rest of the template remains the same -->
        <v-app-bar elevation="2">
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

            <v-app-bar-title>
                <Link :href="route('dashboard')" class="d-flex align-center text-decoration-none">
                    <ApplicationMark class="d-inline-block" style="height: 36px;" />
                </Link>
            </v-app-bar-title>

            <v-spacer></v-spacer>

            <!-- Teams Dropdown -->
            <v-menu v-if="$page.props.jetstream.hasTeamFeatures">
                <template v-slot:activator="{ props }">
                    <v-btn variant="text" v-bind="props">
                        {{ $page.props.auth.user.current_team.name }}
                        <v-icon>mdi-chevron-down</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-subheader>Manage Team</v-list-subheader>

                    <v-list-item
                        :href="route('teams.show', $page.props.auth.user.current_team)"
                        title="Team Settings"
                    ></v-list-item>

                    <v-list-item
                        v-if="$page.props.jetstream.canCreateTeams"
                        :href="route('teams.create')"
                        title="Create New Team"
                    ></v-list-item>

                    <v-divider v-if="$page.props.auth.user.all_teams.length > 1"></v-divider>

                    <template v-if="$page.props.auth.user.all_teams.length > 1">
                        <v-list-subheader>Switch Teams</v-list-subheader>

                        <v-list-item
                            v-for="team in $page.props.auth.user.all_teams"
                            :key="team.id"
                            @click="switchToTeam(team)"
                        >
                            <template v-slot:prepend>
                                <v-icon v-if="team.id == $page.props.auth.user.current_team_id" color="success">
                                    mdi-check-circle
                                </v-icon>
                            </template>
                            <v-list-item-title>{{ team.name }}</v-list-item-title>
                        </v-list-item>
                    </template>
                </v-list>
            </v-menu>

            <!-- User Dropdown -->
            <v-menu>
                <template v-slot:activator="{ props }">
                    <v-btn variant="text" v-bind="props">
                        <v-avatar v-if="$page.props.jetstream.managesProfilePhotos" size="32">
                            <v-img :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name"></v-img>
                        </v-avatar>
                        <span v-else>{{ $page.props.auth.user.name }}</span>
                        <v-icon>mdi-chevron-down</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-subheader>Manage Account</v-list-subheader>

                    <v-list-item
                        :href="route('profile.show')"
                        title="Profile"
                    ></v-list-item>

                    <v-list-item
                        v-if="$page.props.jetstream.hasApiFeatures"
                        :href="route('api-tokens.index')"
                        title="API Tokens"
                    ></v-list-item>
                    <v-divider></v-divider>

                    <v-list-item
                        @click="toggleTheme"
                        title="Toggle Theme"
                    ></v-list-item>

                    <v-divider></v-divider>

                    <v-list-item
                        @click="logout"
                        title="Log Out"
                    ></v-list-item>
                </v-list>
            </v-menu>


        </v-app-bar>

        <!-- Navigation Drawer -->
        <v-navigation-drawer
            v-model="drawer"
            :rail="rail"
            permanent
            elevation="2"
        >
            <v-list>
                <v-list-item
                    :prepend-avatar="$page.props.auth.user.profile_photo_url"
                    :title="$page.props.auth.user.name"
                    :subtitle="$page.props.auth.user.email"
                    :href="route('profile.show')"
                >
                </v-list-item>
            </v-list>

            <v-divider></v-divider>

            <div class="flex justify-center p-4">
                <v-btn type="submit" color="primary" @click="navigateTo('NewLinkPage')" class="flex justify-center items-center" block>
                Create Link
                </v-btn>
            </div>

            <v-divider></v-divider>

            <v-list>
                <v-list-item
                    link
                    @click="navigateTo('dashboard')"
                    :active="route().current('dashboard')"
                    prepend-icon="mdi-view-dashboard"
                    title="Dashboard"
                    value="dashboard"
                ></v-list-item>

                <v-list-item
                    link
                    @click="navigateTo('showAllShortlinks')"
                    :active="route().current('showAllShortlinks')"
                    prepend-icon="mdi-link-variant"
                    title="Links"
                    value="manage-shortlinks"
                ></v-list-item>

                <v-list-item
                    link
                    @click="navigateTo('dashboard')"
                    :active="route().current('dashboard')"
                    prepend-icon="mdi-qrcode-scan"
                    title="QR Codes"
                    value="qr-codes"
                ></v-list-item>

                <v-list-item
                    link
                    @click="navigateTo('dashboard')"
                    :active="route().current('dashboard')"
                    prepend-icon="mdi-file-document-outline"
                    title="Pages"
                    value="pages"
                ></v-list-item>

                <v-list-item
                    link
                    @click="navigateTo('dashboard')"
                    :active="route().current('dashboard')"
                    prepend-icon="mdi-google-analytics"
                    title="Analytics"
                    value="analytics"
                ></v-list-item>

                <v-list-item
                    link
                    @click="navigateTo('dashboard')"
                    :active="route().current('dashboard')"
                    prepend-icon="mdi-folder-outline"
                    title="Campaigns"
                    value="campaigns"
                ></v-list-item>

                <v-list-item
                    link
                    @click="navigateTo('dashboard')"
                    :active="route().current('dashboard')"
                    prepend-icon="mdi-folder-outline"
                    title="Custom Domains"
                    value="custom-domains"
                ></v-list-item>

                <v-divider></v-divider>

                <v-list-item
                    prepend-icon="mdi-cog"
                    title="Settings"
                    @click="navigateTo('settings')"
                    :active="route().current('settings')"
                ></v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main>
            <v-container fluid>
                <slot />
            </v-container>
        </v-main>

        <v-footer>
            <!-- Footer content here -->
        </v-footer>
    </v-app>
</template>
