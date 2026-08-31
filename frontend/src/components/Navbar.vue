<template>
  <nav class="navbar glass-panel">
    <div class="nav-container">
      <div class="brand">
        <span class="brand-text text-gradient">Venom</span>
      </div>

      <div class="nav-controls">
        
        <div class="user-info">
          <div class="info-pill" v-if="currentOrganizationName">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
               <path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
               <path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.4 5.4 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2z"/>
            </svg>
            <span>{{ currentOrganizationName }}</span>
          </div>
          
          <div class="info-pill role" v-if="currentRoleName">
             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
               <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4z"/>
             </svg>
            <span>{{ currentRoleName }}</span>
          </div>
        </div>

        <button class="menu-toggle btn-venom" @click="toggleMenu">
           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16" v-if="!isMenuOpen">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
          </svg>
           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16" v-else>
              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
           </svg>
        </button>

      </div>
    </div>

    <!-- Mobile/Drawer Menu -->
    <div class="nav-drawer glass-panel" :class="{ 'is-open': isMenuOpen }">
      <ul class="nav-list">
        <li class="nav-item">
          <a class="nav-link" href="#" v-if="authStore.selectionsLocked == true" @click="handleNav('dashboard')">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
              <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
              <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
            </svg>
            Inicio
          </a>
        </li>
        
        <li class="nav-item" v-if="authStore.selectionsLocked">
           <a class="nav-link" href="#" @click="changeSelection">
               <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                  <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
                </svg>
                Cambiar Org/Rol
           </a>
        </li>

         <li class="nav-item">
            <a class="nav-link" href="#" @click="toggleUserMenu">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
                Mi Perfil
            </a>
            <div class="sub-menu" v-if="showUserMenu">
                <button @click="handleLogout" class="btn-venom secondary small">Cerrar Sesión</button>
            </div>
         </li>

         <li v-for="menu in authStore.authUserMenus" :key="menu.menu.name" class="nav-item" v-if="authStore.selectionsLocked == true">
             <RecursiveMenu :item="menu.menu" @navigate="goToRoute" />
         </li>

      </ul>
    </div>
  </nav>
</template>

<script>
import { computed, ref } from 'vue';
import { useAuthStore } from '@/stores/modules/auth';
import { useRouter } from 'vue-router';
import RecursiveMenu from './RecursiveMenu.vue';

export default {
  components: { RecursiveMenu },
  setup() {
    const authStore = useAuthStore();
    const router = useRouter();
    const user = computed(() => authStore.user);
    const isMenuOpen = ref(false);
    const showUserMenu = ref(false);

    const toggleMenu = () => isMenuOpen.value = !isMenuOpen.value;
    const toggleUserMenu = () => showUserMenu.value = !showUserMenu.value;

    const handleNav = async (ruta) => {
      isMenuOpen.value = false;
      await router.push({ name: ruta });
    };
    
    const handleLogout = async () => {
      try {
        await authStore.logout(router);
      } catch (error) {
        console.error('Error al cerrar sesión:', error);
      }
    };

    const currentOrganizationName = computed(() => {
      const org = (authStore.authUserOrganizations || []).find(o => o.id == authStore.activeOrganizationId)
      return org ? org.name : '-'
    })

    const currentRoleName = computed(() => {
      const role = (authStore.authOrganizationRol || []).find(r => r.id == authStore.activeOrganizationRoleId)
      return role ? role.rol.name : '-'
    })

    const changeSelection = async () => {
      authStore.unlockSelections()
      isMenuOpen.value = false;
      await router.push({ name: 'OrganizationRolSelector' })
    }
    
    const goToRoute = (routeName) => {
      if (!routeName) return;
      isMenuOpen.value = false;
      router.push({ name: routeName });
    };

    return { 
      user, 
      handleLogout, 
      handleNav, 
      authStore, 
      currentOrganizationName, 
      goToRoute, 
      currentRoleName, 
      changeSelection,
      isMenuOpen,
      toggleMenu,
      showUserMenu,
      toggleUserMenu
    };
  },
};
</script>

<style scoped>
/* Base Navbar (Mobile First) */
.navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: var(--header-height);
  z-index: 1000;
  padding: 0 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: rgba(10, 10, 10, 0.8);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid var(--glass-border);
}

.nav-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.brand-text {
  font-size: 1.5rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.nav-controls {
  display: flex;
  align-items: center;
  gap: 1rem;
}

/* User Info - Hidden on mobile, handled in Sidebar on Desktop */
.user-info {
  display: none; 
}

.menu-toggle {
    padding: 0.4rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Drawer (Mobile Menu) */
.nav-drawer {
    position: fixed;
    top: var(--header-height);
    left: 0;
    width: 100%;
    height: 0; /* Closed */
    overflow: hidden;
    transition: height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    background: rgba(10, 10, 10, 0.95);
    z-index: 999;
    border-bottom: 1px solid var(--glass-border);
}

.nav-drawer.is-open {
    height: calc(100vh - var(--header-height));
    overflow-y: auto;
    padding: 1rem;
}

/* Styling for Nav Items */
.nav-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.nav-item {
    width: 100%;
}

/* Global Link Styles */
::v-deep(.nav-link) {
  display: flex;
  align-items: center;
  gap: 1rem; /* More space for icons */
  padding: 0.8rem 1rem;
  color: var(--text-muted);
  text-decoration: none;
  border-radius: var(--radius-sm);
  transition: all 0.2s;
  font-weight: 500;
  cursor: pointer;
}

::v-deep(.nav-link:hover), ::v-deep(.nav-link.active) {
  background: rgba(255,255,255,0.05);
  color: var(--primary);
}

::v-deep(.nav-link i), ::v-deep(.nav-link svg) {
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

/* -------- PRO SIDEBAR (DESKTOP) -------- */
@media (min-width: 992px) {
  .navbar {
    position: sticky; /* Sticky in the flex container */
    top: 0;
    width: var(--sidebar-width);
    height: 100vh;
    flex-direction: column;
    justify-content: flex-start;
    padding: 2rem 1.5rem;
    background: rgba(20, 20, 20, 0.6); /* Slightly more transparent */
    border-right: 1px solid var(--glass-border);
    border-bottom: none;
    align-items: flex-start; /* Align content to start */
  }

  .nav-container {
    flex-direction: column;
    align-items: flex-start;
    height: auto;
    width: 100%;
    margin-bottom: 2rem;
  }

  .nav-controls {
    display: none; /* Hide mobile toggles */
  }

  /* Show Drawer content permanently in Sidebar */
  .nav-drawer {
    position: static;
    width: 100%;
    height: auto !important;
    background: transparent;
    border: none;
    padding: 0;
    overflow-y: auto; /* Permitir scroll cuando hay muchos menús */
    max-height: calc(100vh - 200px); /* Limitar altura para forzar scroll si es necesario */
  }

  .nav-list {
    gap: 0.8rem;
  }
}
</style>
