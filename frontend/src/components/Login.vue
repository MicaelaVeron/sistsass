<!-- resources/js/views/Login.vue -->
<template>
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100"> 
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="handleSubmit">
            <div class="form-group ">
              <label for="email" class="col-md-4 col-form-label text-md-right">Correo Electrónico</label>
              <input
                class="form-control"
                id="email"
                v-model="form.email"
                type="email"
                required
                placeholder="tu@email.com"
              >
            </div>
            <div class="form-group ">
                <label for="password"  class="col col-form-label text-md-right">Contraseña</label>
                  <input
                    class="form-control"
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    placeholder="••••••••"
                  >
            </div>
            <div class="form-group row p-3">
              <button type="submit" :disabled="loading">
                {{ loading ? 'Cargando...' : 'Iniciar Sesión' }}
              </button>
            </div>
          </form>
          <ErrorDisplay  />
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
          await authStore.login(form.value.email, form.value.password,router);
        } catch (error) {
          errorStore.setError(error); 
        } finally {
          loading.value = false;
        }
      };
      
      return { form,loading, handleSubmit };
    }
  };
  </script>
  
  <style scoped>

  .card {
    max-height: 600px;
  max-width: 800px; /* Limita el ancho máximo del formulario */
  width: 100%; /* Asegura que ocupe todo el ancho disponible */
  padding: 3rem; /* Espaciado interno */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra para mejorar el diseño */
  background-color: #f8f9fa; /* Color de fondo claro */
  border-radius: 8px; /* Bordes redondeados */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra */
}

button {
  background-color: #3490dc;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.error {
  color: red;
  font-size: 0.8rem;
}

.error-message {
  margin-top: 1rem;
  color: red;
}
  </style>