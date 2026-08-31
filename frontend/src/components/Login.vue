<template>
  <div class="login-container"> 
    <div class="login-card glass-panel">
      
      <div class="card-header">
        <h1 class="text-gradient">Bienvenido</h1>
        <p class="subtitle">Inicia sesión en tu cuenta</p>
      </div>

      <form @submit.prevent="handleSubmit" class="login-form">
        <div class="form-group">
          <label for="email">Correo Electrónico</label>
          <input
            class="input-venom"
            id="email"
            v-model="form.email"
            type="email"
            required
            placeholder="nombre@empresa.com"
          >
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
              <input
                class="input-venom"
                id="password"
                v-model="form.password"
                type="password"
                required
                placeholder="••••••••"
              >
        </div>
        
        <div class="form-actions">
          <button type="submit" :disabled="loading" class="btn-venom primary full-width">
            {{ loading ? 'Ingresando...' : 'Iniciar Sesión' }}
          </button>
        </div>
      </form>
      
      <div class="card-footer">
        <ErrorDisplay />
      </div>

    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/modules/auth';
import { useErrorMessages } from '@/stores/modules/errors_messages';
import ErrorDisplay from '@/components/ErrorDisplay.vue';

export default {
  components: {
    ErrorDisplay,
  },
  setup() {
    const authStore = useAuthStore();
    const router = useRouter();
    const errorStore = useErrorMessages();
    const form = ref({
      email: '',
      password: ''
    });
    
    const loading = ref(false);
    
    const handleSubmit = async () => {
      loading.value = true;
      errorStore.clearErrors();
      
      try {
        await authStore.login(form.value.email, form.value.password, router);
      } catch (error) {
        errorStore.setError(error); 
      } finally {
        loading.value = false;
      }
    };
    
    return { form, loading, handleSubmit };
  }
};
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  width: 100%;
  padding: 1rem;
}

.login-card {
  width: 100%;
  max-width: 420px; /* More compact */
  padding: 2.5rem;
  display: flex;
  flex-direction: column;
  gap: 2rem;
  /* Glass effect from global is applied class="glass-panel" */
}

.card-header {
  text-align: center;
}

.card-header h1 {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.subtitle {
  color: var(--text-muted);
  font-size: 0.95rem;
  margin: 0;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group label {
  color: var(--text-muted);
  font-size: 0.85rem;
  font-weight: 500;
  margin-left: 2px;
}

.form-actions {
  margin-top: 1rem;
}

.full-width {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Animations */
.login-card {
  animation: fadeInDown 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>