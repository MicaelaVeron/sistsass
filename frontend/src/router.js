import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/modules/auth';
import Navbar from './components/Navbar.vue';
import Login from './components/Login.vue' 
import Dashboard from './components/Dashboard.vue';
import OrganizationRolSelector from './components/OrganizationRolSelector.vue';
const routes = [
  {
    path: '/',
    redirect: '/login', // Redirige al login al acceder a la raíz
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/components/Login.vue'),
    meta: { guestOnly: true }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/components/Dashboard.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/navbar',
    name: 'navbar',
    component: () => import('@/components/Navbar.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/MenuList',
    name: 'MenuList',
    component: () => import('@/components/menus/MenuList.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/OrganizationList',
    name: 'OrganizationList',
    component: () => import('@/components/organizations/OrganizationList.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/BranchList',
    name: 'BranchList',
    component: () => import('@/components/branches/BranchList.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/RolList',
    name: 'RolList',
    component: () => import('@/components/roles/RolList.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/PermissionList',
    name: 'PermissionList',
    component: () => import('@/components/permissions/PermissionList.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/UserList',
    name: 'UserList',
    component: () => import('@/components/users/UserList.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/OrganizationRolSelector',
    name: 'OrganizationRolSelector',
    component: () => import('@/components/OrganizationRolSelector.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/login', // Redirige cualquier ruta no encontrada al login
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();
  
  // Si la ruta requiere autenticación
  if (to.meta.requiresAuth) {
    if (authStore.isAuthenticated) {
      // Si no tenemos datos del usuario, los obtenemos
      if (!authStore.user) {
        try {
          await authStore.fetchUser();
          next();
        } catch (error) {
          authStore.reset();
          next({ name: 'login', query: { redirect: to.fullPath } });
        }
      } else {
        next();
      }
    } else {
      // Guardar la URL a la que intentaba acceder
      authStore.returnUrl = to.fullPath;
      next({ name: 'login' });
    }
  } 
  // Si la ruta es solo para invitados
  else if (to.meta.guestOnly && authStore.isAuthenticated) {
    next({ name: 'dashboard' });
  } 
  // Para cualquier otra ruta
  else {
    next();
  }
});

export default router;