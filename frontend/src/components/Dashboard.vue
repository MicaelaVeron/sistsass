<!-- resources/js/views/Dashboard.vue -->
<template>
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100">
      <h1>Bienvenido, {{ (user) ? user.name : '' }}</h1>
    </div>
  </template>
  
  <script>
  import { computed } from 'vue';
  import { useAuthStore } from '@/stores/modules/auth';
  import { useRouter } from 'vue-router'
  export default {
    setup() {
      const authStore = useAuthStore();
      const router = useRouter();
      const user = computed(() => authStore.user);
      return { user };
    }
  };
  </script>