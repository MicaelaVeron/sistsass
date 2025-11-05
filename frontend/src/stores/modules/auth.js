import { defineStore } from 'pinia';
import { useRouter } from 'vue-router'
import axios from '../../axios';
export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
        returnUrl: null
    }),
    actions: {
        async login(email, password,router) {
            try {
                const response = await axios.post('/api/login', { email, password });
                this.token = response.data.token;
                this.user = response.data.user;
                localStorage.setItem('token', this.token);  
                // Redirigir a la ruta previa o al dashboard
                router.push('/dashboard');
                
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        async logout(router) {
            try {
                await axios.post('/api/logout');
                this.reset();
                router.push('/login');
                //console.log(router);
            } catch (error) {
                console.error('Logout error:', error);
                throw error;
            }
        },
        async fetchUser() {
            try {
                const response = await axios.get('/api/user');
                this.user = response.data;
                return this.user;
            } catch (error) {
                this.reset();
                throw error;
            }
        },
        reset() {
            this.user = null;
            this.token = null;
           // this.returnUrl = null;
            localStorage.removeItem('token');
        }
    },
    getters: {
        isAuthenticated: (state) => !!state.user && !!state.token,
    }
});