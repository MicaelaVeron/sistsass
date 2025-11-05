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
              <label for="url" class="form-label">N° de Sucursal</label>
              <input
                type="text"
                id="url"
                v-model="branch.form.number"
                class="form-control"
                placeholder="Ingrese el numero de la sucursal"
                required
              />
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Nombre de la sucursal</label>
              <input
                type="text"
                id="name"
                v-model="branch.form.name"
                class="form-control"
                placeholder="Ingrese el nombre de la sucursal"
                required
              />
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Direccion de la sucursal</label>
              <input
                type="text"
                id="address"
                v-model="branch.form.address"
                class="form-control"
                placeholder="Ingrese la direccion de la sucursal"
                required
              />
            </div>
            <div class="mb-3">
              <label for="telephone" class="form-label">Telefono de la sucursal</label>
              <input
                type="text"
                id="telephone"
                v-model="branch.form.telephone"
                class="form-control"
                placeholder="Ingrese el telefono de la sucursal"
                required
              />
            </div>
            <div class="mb-3">
              <label for="organization_id" class="form-label">Organizacion</label>
              <select
                id="organization_id"
                v-model="branch.form.organization_id"
                class="form-select"
              >
              <option :value="null">Sin Organizacion</option>
              <option
                v-for="organization in organization.organizations"
                :key="organization.id"
                :value="organization.id"
              >
                {{ organization.name }}
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
  import { useBranch } from '@/stores/modules/branch';
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  import ErrorDisplay from '@/components/ErrorDisplay.vue';
  import { useOrganization } from '@/stores/modules/organization';
  import { onMounted } from 'vue';
  export default {
    components: {
      ErrorDisplay,
    },
    setup() {
      const branch = useBranch();
      const errorStore = useErrorMessages();
      const organization = useOrganization();
      onMounted(() => {
        organization.fetchOrganizations(); // Carga las organizaciones al montar el componente
      });
      const handleSubmit = async () =>{
        errorStore.clearErrors();
        try {
          await branch.send();
          branch.reset(); 
          errorStore.setSuccess();
          errorStore.setComponentType(2);
          branch.fetch(); // Refetch menus after saving
        } catch (error) {
          errorStore.setError(error); 
          errorStore.setComponentType(2);
        } 
      };

      return { branch,errorStore ,handleSubmit,organization };
    },
  };
  </script>
