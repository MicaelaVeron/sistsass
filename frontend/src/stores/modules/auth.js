import { defineStore } from 'pinia';
import { useRouter } from 'vue-router'
import axios from '../../axios';
export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('token') || null,
        user: JSON.parse(localStorage.getItem('user')) || null,
        returnUrl: null,
        authUserOrganizations: [],
        authOrganizationRol: [],
        authUserMenus: [],
        // Selección actual que controla qué organización/rol está activo en la sesión
        activeOrganizationId: localStorage.getItem('activeOrganizationId') || null,
        activeOrganizationRoleId: localStorage.getItem('activeOrganizationRoleId') || null,
        // Si está true, ocultamos los selectores en el dashboard y sólo mostramos opción en el menú para cambiar
        selectionsLocked: JSON.parse(localStorage.getItem('selectionsLocked') || 'false'),
    }),
    actions: {
        async login(email, password, router) {
            try {
                const response = await axios.post('/api/login', { email, password });
                this.token = response.data.token;
                this.user = response.data.user;
                // Si el backend devuelve organizaciones/roles en el login, las guardamos
                if (response.data.organizations) {
                    this.authUserOrganizations = response.data.organizations;
                    localStorage.setItem('authUserOrganizations', JSON.stringify(this.authUserOrganizations));
                }
                localStorage.setItem('token', this.token);
                localStorage.setItem('user', JSON.stringify(this.user));
                // Redirigir a la ruta previa o al dashboard
                router.push('/OrganizationRolSelector');

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
                if (router) router.push('/login');
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
                localStorage.setItem('user', JSON.stringify(response.data));
                // Si el endpoint /api/user incluye organizaciones/roles, actualizar y persistir
                if (response.data.organizations) {
                    this.authUserOrganizations = response.data.organizations;
                    localStorage.setItem('authUserOrganizations', JSON.stringify(this.authUserOrganizations));
                }
                return this.user;
            } catch (error) {
                this.reset();
                throw error;
            }
        },
        async fetchOrganizationRol() {
            try {
                const response = await axios.get(`/api/user-getOrganizationAndAssignedRolesForOrganization/${this.user.id}/${this.activeOrganizationId}`);
                this.authOrganizationRol = response.data;
                localStorage.setItem('authOrganizationRol', JSON.stringify(this.authOrganizationRol));
            } catch (error) {
                console.error('Logout error:', error);
                throw error;
            }
        },
        // Establecer la organización activa (id)
        setActiveOrganization(orgId) {
            this.activeOrganizationId = orgId;
            // al cambiar organización limpiamos rol activo
            this.activeOrganizationRoleId = null;
            localStorage.setItem('activeOrganizationId', orgId);
            localStorage.removeItem('activeOrganizationRoleId');
            this.selectionsLocked = false;
            localStorage.setItem('selectionsLocked', JSON.stringify(this.selectionsLocked));
            this.fetchOrganizationRol();
        },
        // Establecer el rol activo y cargar los menús correspondientes
        async setActiveRole(roleId) {
            this.activeOrganizationRoleId = roleId;
            localStorage.setItem('activeOrganizationRoleId', roleId);
            // Intentar obtener menús para el rol desde un endpoint si existe
            this.getMenus();
        },
        lockSelections() {
            this.selectionsLocked = true;
            localStorage.setItem('selectionsLocked', JSON.stringify(this.selectionsLocked));

        },
        unlockSelections() {
            this.selectionsLocked = false;
            localStorage.setItem('selectionsLocked', JSON.stringify(this.selectionsLocked));
            // opcional: conservar activeOrganizationId pero limpiar activeOrganizationRoleId para forzar reelección
            this.activeOrganizationRoleId = null;
            localStorage.removeItem('activeOrganizationRoleId');
        },
        async getMenus() {
            try {
                const response = await axios.get(`/api/menu-getMenusForOrganizationRol/${this.activeOrganizationRoleId}`);
                this.authUserMenus = response.data;
            } catch (err) {
                console.error('Logout error:', err);
                throw err;
            }
            localStorage.setItem('authUserMenus', JSON.stringify(this.authUserMenus));
        },
        async updateUserContext() {
            try {
                // Call API to persist selection
                await axios.post('/api/user-context', {
                    last_organization_id: this.activeOrganizationId,
                    last_role_id: this.activeOrganizationRoleId
                });
                // Update local user object as well
                if (this.user) {
                    this.user.last_organization_id = this.activeOrganizationId;
                    this.user.last_role_id = this.activeOrganizationRoleId;
                    localStorage.setItem('user', JSON.stringify(this.user));
                }
            } catch (error) {
                console.error('Error updating context:', error);
                // We don't throw here to avoid blocking login if this fails
            }
        },
        reset() {
            this.user = null;
            this.token = null;
            // this.returnUrl = null;
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            localStorage.removeItem('authUserOrganizations');
            localStorage.removeItem('authOrganizationRol');
            localStorage.removeItem('authUserMenus');
            this.authUserOrganizations = [];
            this.authOrganizationRol = [];
            this.authUserMenus = [];
            // limpiar selecciones activas
            this.activeOrganizationId = null;
            this.activeOrganizationRoleId = null;
            this.selectionsLocked = false;
            localStorage.removeItem('activeOrganizationId');
            localStorage.removeItem('activeOrganizationRoleId');
            localStorage.removeItem('selectionsLocked');
        }
    },
    getters: {
        // Consideramos autenticado si hay token; el fetchUser validará el token real.
        isAuthenticated: (state) => !!state.token,
        // Roles filtrados por la organización activa
        rolesForActiveOrg: (state) => {
            if (!state.activeOrganizationId) return [];
            return state.authOrganizationRol.filter(r => {
                // defensivo: algunos roles pueden usar `organization_id` o `org_id`
                return r.organization_id == state.activeOrganizationId;
            })
        },
    }
});