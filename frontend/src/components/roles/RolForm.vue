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
              <label for="name" class="form-label">Nombre</label>
              <input
                type="text"
                id="name"
                v-model="rol.form.name"
                class="form-control"
                placeholder="Ingrese el nombre del rol"
                required
              />
            </div>
            <div class="mb-3">
              <label for="guard_name" class="form-label">Guard Name</label>
              <select
                id="guard_name"
                v-model="rol.form.guard_name"
                class="form-select"
                required
              >
              <option :value="null">Seleccione un guard name</option>
              <option
                v-for="guard in rol.guards_name"
                :key="guard.id"
                :value="guard.value"
              >
                {{ guard.value }}
              </option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary"  v-if="errorStore.status == null">Guardar</button>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue';
  import { useRol } from '@/stores/modules/rol';
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  import ErrorDisplay from '@/components/ErrorDisplay.vue';
  export default {
    components: {
      ErrorDisplay,
    },
    setup() {
      const rol = useRol();
      const errorStore = useErrorMessages();
      const handleSubmit = async () =>{
        errorStore.clearErrors();
        try {
         await rol.send();
          rol.reset();
          errorStore.setSuccess();
          errorStore.setComponentType(2);
        } catch (error) {
          errorStore.setError(error); 
          errorStore.setComponentType(2);
        }
      };
  
      return { errorStore,rol, handleSubmit };
    },
  };
  </script>
