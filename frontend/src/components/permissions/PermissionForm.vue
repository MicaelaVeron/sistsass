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
              <label for="description" class="form-label">Descripción</label>
              <input
                type="text"
                id="description"
                v-model="permission.form.name"
                class="form-control"
                placeholder="Ingrese la descripción"
                required
              />
            </div>
            <div class="mb-3">
              <label for="code" class="form-label">Código</label>
              <input
                type="text"
                id="code"
                v-model="permission.form.code"
                class="form-control"
                placeholder="Ingrese un código"
                required
              />
            </div>
            <div class="mb-3">
              <label for="guard_name" class="form-label">Guard Name</label>
              <select
                id="guard_name"
                v-model="permission.form.guard_name"
                class="form-select"
                required
              >
              <option :value="null">Seleccione un guard name</option>
              <option
                v-for="guard in permission.guards_name"
                :key="guard.id"
                :value="guard.value"
              >
                {{ guard.value }}
              </option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue';
  import { usePermission } from '@/stores/modules/permission';
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  import ErrorDisplay from '@/components/ErrorDisplay.vue';
  export default {
    components: {
      ErrorDisplay,
    },
    setup() {
      const permission = usePermission();
      const errorStore = useErrorMessages();
      const handleSubmit = async () =>{
        errorStore.clearErrors();
        try {
         await permission.send();
          permission.reset();
          errorStore.setSuccess();
          errorStore.setComponentType(2);
        } catch (error) {
          errorStore.setError(error); 
          errorStore.setComponentType(2);
        }
      };
  
      return { errorStore,permission, handleSubmit };
    },
  };
  </script>
