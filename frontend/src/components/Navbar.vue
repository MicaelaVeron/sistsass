<!-- filepath: /home/mica/proyectos/venom11/core/frontend/src/components/Navbar.vue -->
<template>
   <nav class="navbar shadow bg-body-tertiary fixed-top">
    <div class="container-fluid">
      <p class="navbar-brand">Venom</p>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvas" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
          <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
               Mi Perfil
              </a>
              <ul class="dropdown-menu">
                <li>
                  <button @click="handleLogout" class="dropdown-item">Cerrar Sesión</button>
                </li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
               Configuración
              </a>
              <ul class="dropdown-menu">
                <li>
                  <button @click="sendRoute('MenuList')" class="dropdown-item">Menus</button>
                </li>
                <li>
                  <button @click="sendRoute('OrganizationList')" class="dropdown-item">Organizaciones</button>
                </li>
                <li>
                  <button @click="sendRoute('BranchList')" class="dropdown-item">Sucursales</button>
                </li>
                <li>
                  <button @click="sendRoute('PermissionList')" class="dropdown-item">Permisos</button>
                </li>
                <li>
                  <button @click="sendRoute('RolList')" class="dropdown-item">Roles</button>
                </li>
                
                <li>
                  <button @click="sendRoute('UserList')" class="dropdown-item">Usuarios</button>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  </template>
  
  <script>

  import { computed } from 'vue';
  import { useAuthStore } from '@/stores/modules/auth';
  import { useRouter } from 'vue-router';
  export default {
    setup() {
      const authStore = useAuthStore();
      const router = useRouter();
      const user = computed(() => authStore.user);

      const sendRoute = async (ruta) => {
        await router.push({ name: ruta });
      };
      const handleLogout = async () => {
        try {
          await authStore.logout(router);
        } catch (error) {
          console.error('Error al cerrar sesión:', error);
        }
      };
  
      return { user, handleLogout,sendRoute };
    },
  };
  </script>
