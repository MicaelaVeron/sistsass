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
              <label for="name" class="form-label">Nombre del Menú</label>
              <input
                type="text"
                id="name"
                v-model="menu.form.name"
                class="form-control"
                placeholder="Ingrese el nombre del menú"
                required
              />
            </div>
            <div class="mb-3">
              <label for="url" class="form-label">Ruta</label>
              <input
                type="text"
                id="url"
                v-model="menu.form.url"
                class="form-control"
                placeholder="Ingrese el nombre de la ruta"
              />
            </div>
            <div class="mb-3">
              <label for="parent_id" class="form-label">Menú Principal</label>
              <select
                id="parent_id"
                v-model="menu.form.parent_id"
                class="form-select"
              >
              <option :value="null">Sin Menú Principal</option>
              <option
                v-for="parentMenu in parentMenus"
                :key="parentMenu.id"
                :value="parentMenu.id"
              >
                {{ parentMenu.name }}
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
  import { useMenu } from '@/stores/modules/menu';
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  import ErrorDisplay from '@/components/ErrorDisplay.vue';
  export default {
    components: {
      ErrorDisplay,
    },
    setup() {
      const menu = useMenu();
      const errorStore = useErrorMessages();
      const parentMenus = computed(() =>
        menu.menus.filter((m) => m.id !== menu.form.id)
      );
      const handleSubmit = async () =>{
        errorStore.clearErrors();
        try {
          await menu.send();
          menu.reset(); 
          errorStore.setSuccess(2);
          errorStore.setComponentType(2);
          menu.fetchMenus(); // Refetch menus after saving
          
        } catch (error) {
          errorStore.setError(error); 
          errorStore.setComponentType(2);
        } 
      };
  
      return { menu,parentMenus,errorStore ,handleSubmit };
    },
  };
  </script>
