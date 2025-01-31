import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
axios.get('/api/user').then(response => {
    console.log('Authenticated User:', response.data);
});
