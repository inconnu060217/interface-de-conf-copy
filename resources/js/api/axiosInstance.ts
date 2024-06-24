import axios from 'axios';

const axiosInstance = axios.create({
    baseURL: 'http://localhost:8000/api/',
});

/**
 * Add an interceptor to add the authentication token to each request, except for login
 */
/*axiosInstance.interceptors.request.use((config) => {
    const token = useUserStore.getState().userSession?.access_token;
    // @ts-ignore
    if (token && !config.url.includes('auth/login')) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
});*/

export default axiosInstance;
