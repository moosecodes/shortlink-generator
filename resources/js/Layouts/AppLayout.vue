<script setup>
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    VApp,
    VAppBar,
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
    VSpacer,
} from 'vuetify/lib/components/index.mjs';

import NavigationDrawer from '@/Components/Navigation/NavigationDrawer.vue';
import { useTheme } from 'vuetify';

defineProps({
    title: String,
});

const theme = useTheme();

const toggleTheme = () => {
    theme.global.name.value = theme.global.current.value.dark
        ? 'light'
        : 'dark';
};

const switchToTeam = (team) => {
    router.put(
        route('current-team.update'),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        },
    );
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <v-app>
        <Head :title="title" />

        <Banner />

        <!-- Rest of the template remains the same -->
        <v-app-bar elevation="2">
            <v-app-bar-title>
                <Link
                    :href="route('landingPage')"
                    class="d-flex align-center text-decoration-none"
                >
                    <ApplicationMark
                        class="d-inline-block"
                        style="height: 36px"
                    />
                </Link>
            </v-app-bar-title>

            <v-spacer />

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
                        :href="
                            route(
                                'teams.show',
                                $page.props.auth.user.current_team,
                            )
                        "
                        title="Team Settings"
                    />

                    <v-list-item
                        v-if="$page.props.jetstream.canCreateTeams"
                        :href="route('teams.create')"
                        title="Create New Team"
                    />

                    <v-divider
                        v-if="$page.props.auth.user.all_teams.length > 1"
                    />

                    <template v-if="$page.props.auth.user.all_teams.length > 1">
                        <v-list-subheader>Switch Teams</v-list-subheader>

                        <v-list-item
                            v-for="team in $page.props.auth.user.all_teams"
                            :key="team.id"
                            @click="switchToTeam(team)"
                        >
                            <template v-slot:prepend>
                                <v-icon
                                    v-if="
                                        team.id ==
                                        $page.props.auth.user.current_team_id
                                    "
                                    color="success"
                                >
                                    mdi-check-circle
                                </v-icon>
                            </template>
                            <v-list-item-title>{{
                                team.name
                            }}</v-list-item-title>
                        </v-list-item>
                    </template>
                </v-list>
            </v-menu>

            <!-- User Dropdown -->
            <v-menu>
                <template v-slot:activator="{ props }">
                    <v-btn variant="text" v-bind="props">
                        <v-avatar
                            v-if="$page.props.jetstream.managesProfilePhotos"
                            size="32"
                        >
                            <v-img
                                :src="$page.props.auth.user.profile_photo_url"
                                :alt="$page.props.auth.user.name"
                            />
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
                    />

                    <v-list-item
                        v-if="$page.props.jetstream.hasApiFeatures"
                        :href="route('api-tokens.index')"
                        title="API Tokens"
                    />

                    <v-divider />

                    <v-list-item @click="toggleTheme" title="Toggle Theme" />

                    <v-divider />

                    <v-list-item @click="logout" title="Log Out" />
                </v-list>
            </v-menu>
        </v-app-bar>

        <NavigationDrawer />

        <v-main>
            <div class="mx-auto max-w-7xl py-12 sm:px-6 lg:px-8">
                <v-container>
                    <slot />
                </v-container>
            </div>
        </v-main>

        <v-footer>
            <!-- Footer content here -->
        </v-footer>
    </v-app>
</template>
