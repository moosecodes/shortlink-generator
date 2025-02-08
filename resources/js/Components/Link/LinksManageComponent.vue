<script setup>
import LinkFilterComponent from '@/Components/Link/LinkFilterComponent.vue';
import LinkDetailsComponent from '@/Components/LinkDetails/LinkDetailsComponent.vue';
import SubMenuComponent from '@/Components/Navigation/SubMenuComponent.vue';
import PageHeaderComponent from '@/Components/PageHeaderComponent.vue';
import { fetchUserShortlinks } from '@/utils/requests';
import { computed, onMounted, reactive } from 'vue';

const state = reactive({
    links: [],
    filter: 'All',
});

// Function to update filter when event is received
const handleFilterChange = (newFilter) => {
    state.filter = newFilter;
};

// Computed property to filter and sort the links based on selection
const filteredlinks = computed(() => {
    if (!state.links) return [];

    let filtered = [];

    // Filtering
    switch (state.filter) {
        case 'All':
            filtered = [...state.links];
            break;
        case 'Active':
            filtered = state.links.filter((link) => link.is_active);
            break;
        case 'Inactive':
            filtered = state.links.filter((link) => !link.is_active);
            break;
        case 'Expired':
            filtered = state.links.filter((link) => link.expired);
            break;
        case 'Not Expired':
            filtered = state.links.filter((link) => !link.expired);
            break;
        default:
            filtered = [...state.links];
            break;
    }

    // Sorting based on clicks (engagements)
    if (state.filter === 'Clicks: High to Low') {
        filtered.sort((a, b) => b.clicks - a.clicks); // Descending order
    } else if (state.filter === 'Clicks: Low to High') {
        filtered.sort((a, b) => a.clicks - b.clicks); // Ascending order
    } else {
        filtered.reverse(); // Default reverse order for recent links
    }

    return filtered;
});

onMounted(async () => {
    state.links = await fetchUserShortlinks();
});
</script>

<template>
    <PageHeaderComponent />

    <div class="mb-4">
        <p v-if="!state.links?.length">
            No short links currently exist. Create a new link to get started!
        </p>
    </div>

    <SubMenuComponent />

    <!-- Pass filteredCount and totalCount to LinkFilterComponent -->
    <LinkFilterComponent
        :filteredCount="filteredlinks.length"
        :totalCount="state.links.length"
        @filterChanged="handleFilterChange"
    />

    <LinkDetailsComponent
        v-if="filteredlinks.length"
        :filteredShortlinks="filteredlinks"
    />
</template>
