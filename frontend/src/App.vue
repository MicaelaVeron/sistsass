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
<template>
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
html, body, #app {
  height: 100%; /* Asegura que el contenedor ocupe toda la altura de la pantalla */
  width: 100%; /* Asegura que ocupe todo el ancho de la pantalla */
  margin: 0; /* Elimina márgenes predeterminados */
  padding: 0; /* Elimina padding predeterminado */
  display: flex; /* Cambia el diseño a flexbox */
  flex-direction: column; /* Asegura que las vistas se apilen verticalmente */
}
</style>