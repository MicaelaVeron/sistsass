<script setup>
  import { provide,computed } from 'vue';
  import { useAuthStore } from '@/stores/modules/auth';
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  const authStore = useAuthStore()
  const ErrorMessages = useErrorMessages()
  provide('authStore', authStore) // Provee el store globalmente
  provide('ErrorMessages', ErrorMessages)  
  const isAuthenticated = computed(() => authStore.isAuthenticated);
</script>
<template >
    <Navbar v-if="isAuthenticated" />
    <router-view></router-view>
</template>
<script>
  import Navbar from '@/components/Navbar.vue';
  export default {
    components: {
      Navbar,
    },
  };
</script>
<style>
/* Layout Global */
html, body, #app {
  height: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
  overflow-x: hidden;
}

#app {
  display: flex;
  flex-direction: column;
  background-color: var(--bg-dark);
  color: var(--text-main);
  min-height: 100vh;
}

/* Desktop Sidebar Layout */
@media (min-width: 992px) {
  #app {
    flex-direction: row; /* Sidebar left, content right */
  }
  
  /* Make direct children (like router-view / wrapper) grow to fill space */
  #app > :not(nav) {
    flex-grow: 1;
    overflow-x: hidden;
  }
}

/* Background Animation Placeholder (Optional) */
body::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: 
    radial-gradient(circle at 15% 50%, rgba(112, 0, 255, 0.08), transparent 25%),
    radial-gradient(circle at 85% 30%, rgba(0, 255, 136, 0.05), transparent 25%);
  z-index: -1;
  pointer-events: none;
}
</style>