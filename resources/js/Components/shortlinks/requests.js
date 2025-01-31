import { router } from '@inertiajs/vue3';

export const deleteShortlink = async ({ short_code }) => {
    const confirmDelete = confirm(`Are you sure you want to delete this shortlink ${short_code}?`);

    if (confirmDelete) {
        try {
            await axios.delete(`/api/link/delete/${short_code}`);
            window.location.reload();
        } catch (error) {
            console.error('Error deleting shortlink:', error);
        }
    }
}

export const toggleActivation = async (shortlink) => {
    console.log('Toggling activation:', shortlink);
    try {
        const route = shortlink.is_active
            ? `/api/link/deactivate/${shortlink.short_code}`
            : `/api/link/activate/${shortlink.short_code}`;

        await axios.patch(route);

        shortlink.is_active = !shortlink.is_active;
    } catch (error) {
        console.error('Error toggling activation:', error);
    }
};

export const fetchShortlink = async (id) => {
    try {
        const response = await axios.post(`/api/link/details`, { id });
        return response;
    } catch (error) {
        console.error('Error fetching shortlink:', error);
    }
};

export const fetchShortlinks = async () => {
    try {
        const response = await axios.get('/api/links/manage');

        return response.data;
    } catch (error) {
        console.error('Error fetching shortlinks:', error);
    }
};
export const updateLink = async (link) => {
    try {
        console.log('Updating shortlink:', link);
        const { id, short_code, user_url, metadatas } = link;
        const response = await axios.patch(`/api/link/edit`, {
            id,
            short_code,
            user_url,
            metadatas,
        });
        console.log('Updated shortlink:', response.data);
    } catch (error) {
        console.error('Error updating shortlink:', error);
        state.message = error;
    }
};

export const redirectedUrls = async (shortlinks) => {
    try {
        const redirects = [];
        const response = await axios.post(`/api/redirects`, { shortlinks: shortlinks });
        const urls = response.data.shortlink_redirect_urls;
        urls.forEach(url => {
            redirects.push({short_code: url.short_code, redirect: url.url});
        });

        return redirects;
    } catch (error) {
        console.error('Error fetching redirected URLs:', error);
    }
}

export const navigateTo = (routeName) => {
    router.get(route(routeName));
};

export default { fetchShortlinks, redirectedUrls, navigateTo, deleteShortlink, toggleActivation };
