// resources/js/axios.js
import axios from 'axios';
import { useAuthStore } from '@/stores/modules/auth';

const api = axios.create({
    baseURL: 'http://localhost:9080',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
});
// Interceptor para añadir el token de autenticación
api.interceptors.request.use(config => {
  const authStore = useAuthStore();
  if (authStore.token) {
    config.headers.Authorization = `Bearer ${authStore.token}`;
  }
  return config;
});

// Interceptor para manejar errores
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      const authStore = useAuthStore();
      authStore.logout();
    }
    return Promise.reject(error);
  }
);

export default api;