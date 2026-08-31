import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './assets/design_system.css'
import './assets/main.css'
import { createPinia } from 'pinia'
import router from './router';
import axios from './axios';
import * as stores from './stores'
import { useAuthStore } from './stores/modules/auth'
import { createApp } from 'vue'
import App from './App.vue'

const app = createApp(App)
if (process.env.NODE_ENV === 'development') {
    app.config.devtools = true; // Habilita Vue DevTools
}
const pinia = createPinia()
app.use(pinia)
app.use(router)

// Inicializar el header Authorization si ya hay token guardado
const initApp = async () => {
    try {
        const authStore = useAuthStore()
        // Restaurar organizaciones/roles/menus desde localStorage antes de intentar fetch
        const storedOrgs = localStorage.getItem('authUserOrganizations')
        const storedRoles = localStorage.getItem('authOrganizationRol')
        const storedMenus = localStorage.getItem('authUserMenus')
        if (storedOrgs) authStore.authUserOrganizations = JSON.parse(storedOrgs)
        if (storedRoles) authStore.authOrganizationRol = JSON.parse(storedRoles)
        if (storedMenus) authStore.authUserMenus = JSON.parse(storedMenus)
        // Restaurar selecciones activas
        const storedActiveOrg = localStorage.getItem('activeOrganizationId')
        const storedActiveRole = localStorage.getItem('activeOrganizationRoleId')
        const storedLocked = localStorage.getItem('selectionsLocked')
        if (storedActiveOrg) authStore.activeOrganizationId = storedActiveOrg
        if (storedActiveRole) authStore.activeOrganizationRoleId = storedActiveRole
        if (storedLocked) authStore.selectionsLocked = JSON.parse(storedLocked)
        if (authStore && authStore.token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`
            try {
                await authStore.fetchUser()
            } catch (e) {
                // Si el token es inválido, limpiamos el store
                authStore.reset()
            }
        }
    } catch (e) {
        console.warn('No se pudo inicializar authStore en main.js:', e)
    }

    app.mount('#app')
}

initApp()
