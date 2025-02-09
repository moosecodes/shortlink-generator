import { router } from '@inertiajs/vue3';

export const deleteShortlink = async (short_code) => {
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

export const toggleActivation = async (id, is_active) => {
    try {
        const route = is_active
            ? `/api/link/deactivate/${id}`
            : `/api/link/activate/${id}`;

        const response = await axios.patch(route);

        return response.data;

    } catch (error) {
        console.error('Error toggling activation:', error);
    }
};

export const fetchShortlinkByShortCode = async (shortCode) => {
    try {
        const response = await axios.post(`/api/link/details`, { id: shortCode });
        return response.data;
    } catch (error) {
        console.error('Error fetching shortlink:', error);
    }
};

export const fetchShortlinkbyShortCode = async (shortCode) => {
    try {
        const response = await axios.post(`/api/link/details`, { id: shortCode });
        return response.data;
    } catch (error) {
        console.error('Error fetching shortlink:', error);
    }
};

export const fetchUserShortlinks = async () => {
    try {
        const response = await axios.get('/api/links/manage');

        return response.data;
    } catch (error) {
        console.error('Error fetching shortlinks:', error);
    }
};

export const updateLink = async (link) => {
    try {
        const { id, short_code, target_url, metadatas } = link;

        const response = await axios.patch(`/api/link/edit/byShortCode`, {
            id,
            short_code,
            target_url,
            metadatas,
        });
    } catch (error) {
        console.error('Error updating shortlink:', error);
    }
};

export const navigateTo = (routeName) => {
    router.get(route(routeName));
};

export default { fetchUserShortlinks, navigateTo, deleteShortlink, toggleActivation, updateLink, fetchShortlinkByShortCode, fetchShortlinkbyShortCode };
