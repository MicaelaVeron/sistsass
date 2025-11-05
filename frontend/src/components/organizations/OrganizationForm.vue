<!-- filepath: /home/mica/proyectos/venom11/core/frontend/src/components/menus/MenuForm.vue -->
<template>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Formulario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ErrorDisplay  />
          <form @submit.prevent="handleSubmit">
            <div class="mb-3">
              <label for="name" class="form-label">Nombre</label>
              <input
                type="text"
                id="name"
                v-model="organization.form.name"
                class="form-control"
                placeholder="Ingrese el nombre de la organización"
                required
              />
            </div>
            <div class="mb-3">
              <label for="ruc" class="form-label">RUC</label>
              <input
                type="text"
                id="ruc"
                v-model="organization.form.ruc"
                class="form-control"
                placeholder="Ingrese el ruc"
                required
              />
            </div>
            <div class="mb-3">
              <label for="telephone" class="form-label">Telefono</label>
              <input
                type="text"
                id="telephone"
                v-model="organization.form.telephone"
                class="form-control"
                placeholder="Ingrese el telefono"
                required
              />
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Direccion</label>
              <input
                type="text"
                id="address"
                v-model="organization.form.address"
                class="form-control"
                placeholder="Ingrese la direccion"
                required
              />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                id="email"
                v-model="organization.form.email"
                class="form-control"
                placeholder="Ingrese el email"
                required
              />
            </div>
            <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input
              type="file"
              id="logo"
              ref="logoInput"
              @change="handleFileUpload"
              class="form-control"
              accept="image/*"
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
  import { useOrganization } from '@/stores/modules/organization';
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  import ErrorDisplay from '@/components/ErrorDisplay.vue';
  export default {
    components: {
      ErrorDisplay,
    },
    setup() {
      const organization = useOrganization();
      const errorStore = useErrorMessages();
      const logoInput = ref(null);
      const handleFileUpload = (event) => {
        organization.form.logo = event.target.files[0];
      };
      const handleSubmit = async () =>{
        errorStore.clearErrors();
        try {
          await organization.send();
          organization.reset(); 
          errorStore.setSuccess(2);
          organization.fetchOrganizations(); // Refetch menus after saving
           // Limpia el input del logo
          if (logoInput.value) {
            logoInput.value.value = '';
          }
        } catch (error) {
          errorStore.setError(error); 
        } 
      };
  
      return { organization, handleSubmit, handleFileUpload,logoInput };
    },
  };
  </script>
