<!-- filepath: /home/mica/proyectos/venom11/core/frontend/src/components/menus/MenuForm.vue -->
<template>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Formulario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ErrorDisplay v-if="errorStore.componentType == 2" />
          <form @submit.prevent="handleSubmit">
            <div class="mb-3">
              <label for="description" class="form-label">Nombre</label>
              <input
                type="text"
                id="description"
                v-model="user.form.name"
                class="form-control"
                placeholder="Ingrese la descripción"
                required
              />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                id="email"
                v-model="user.form.email"
                class="form-control"
                placeholder="Ingrese el email"
                required
              />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input
                type="password"
                id="password"
                v-model="user.form.password"
                class="form-control"
                placeholder="Ingrese la contraseña"
                
              />
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue';
  import { useUser } from '@/stores/modules/user';
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  import ErrorDisplay from '@/components/ErrorDisplay.vue';
  export default {
    components: {
      ErrorDisplay,
    },
    setup() {
      const user = useUser();
      const errorStore = useErrorMessages();
      const handleSubmit = async () =>{
        errorStore.clearErrors();
        try {
         await user.send();
          user.reset();
          errorStore.setSuccess();
          errorStore.setComponentType(2);
        } catch (error) {
          errorStore.setError(error); 
          errorStore.setComponentType(2);
        }
      };
  
      return { errorStore,user, handleSubmit };
    },
  };
  </script>
