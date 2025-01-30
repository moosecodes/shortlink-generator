import { router } from '@inertiajs/vue3';

export const fetchShortlinks = async (userId) => {
    try {
        const response = await axios.post('/api/links/manage' , {
            userId: userId,
        });

        return response.data;
    } catch (error) {
        console.error('Error fetching shortlinks:', error);
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

export default { fetchShortlinks, redirectedUrls, navigateTo };
