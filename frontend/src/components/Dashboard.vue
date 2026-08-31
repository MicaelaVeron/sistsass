<!-- resources/js/views/Dashboard.vue -->
<template>
  <div class="dashboard-page">
    <div class="content-wrapper glass-panel">
      
      <header class="page-header">
        <h2 class="text-gradient">Dashboard</h2>
        <p class="subtitle">Bienvenido de nuevo, {{ user?.name || 'Usuario' }}</p>
      </header>

      <div class="dashboard-grid">
         <!-- Content placeholder -->
         <div class="card-venom">
            <h3>Resumen</h3>
            <p>Selecciona una opción del menú para comenzar.</p>
         </div>
      </div>

    </div>
  </div>
</template>

<script>
  import { useAuthStore } from '@/stores/modules/auth';
  import { computed } from 'vue';
  
  export default {
    setup() {
      const authStore = useAuthStore();
      const user = computed(() => authStore.user);
      return { user };
    },
  };
</script>

<style scoped>

.dashboard-page {
  /* Removed top padding as navbar is sidebar on desktop */
  flex: 1;
  width: 100%;
  display: flex;
  justify-content: center;
  padding: 2rem;
  overflow-y: auto;
}

@media (max-width: 991px) {
  .dashboard-page {
    padding-top: 100px; /* Space for mobile header */
  }
}


.content-wrapper {
  width: 100%;
  max-width: 1200px;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.page-header h2 {
  font-size: 2.5rem;
  margin-bottom: 0.5rem;
}

.subtitle {
  color: var(--text-muted);
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
}

.card-venom {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid var(--glass-border);
  border-radius: var(--radius-md);
  padding: 1.5rem;
  transition: var(--transition-fast);
}

.card-venom:hover {
  background: rgba(255, 255, 255, 0.06);
  transform: translateY(-5px);
}

.card-venom h3 {
  color: var(--primary);
  margin-bottom: 0.5rem;
}
</style>