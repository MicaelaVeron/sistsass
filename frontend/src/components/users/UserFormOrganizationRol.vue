<template>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Organizaciones y Roles</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ErrorDisplay  />
          <form @submit.prevent="save">
            <div class="row">
                <div class="col-6">
                  <label for="organization_id" class="form-label">Organización</label>
                  <select
                    id="organization_id"
                    v-model="userStore.organization_id"
                    class="form-select"
                    @change="getOrganizationRol($event.target.value)"
                  >
                  <option :value="null">Seleccione</option>
                  <option
                    v-for="organization in userStore.organizations"
                    :key="organization.id"
                    :value="organization.id"
                  >
                    {{ organization.name }}
                  </option>
                  </select>
                </div>
                <div class="col-6">
                  <label for="rol_id" class="form-label">Rol</label>
                  <select
                    id="rol_id"
                    v-model="userStore.rol_id"
                    class="form-select"
                  >
                  <option :value="null">Seleccione</option>
                  <option
                    v-for="rol in userStore.roles"
                    :key="rol.id"
                    :value="rol.id"
                  >
                    {{ rol.name }}
                  </option>
                  </select>
                </div>
            </div>    
            <div class="row justify-content-around pt-3">
              <button type="button"  
							  class="btn btn-success mb-2 col-12" @click="addOrganizationRol">Agregar
              </button>
            </div> 
            <div class="table-responsive col-12">
              <table class="table">
                <thead class="table-dark">
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Organización</th>
                    <th class="text-center">Rol</th>
                    <th class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(organizationRol,index) in userStore.formOrganizationRol.selectedOrganizationRol" :key="organizationRol.id">
                    <td class="text-center" >{{ index + 1 }}</td>
                    <td class="text-center" >{{ organizationRol.organization.name }}</td>
                    <td class="text-center" >{{ organizationRol.rol.name }}</td>
                    <td class="text-center">
                      <button @click="removeOrganizationRol(organizationRol.id,organizationRol.organization_id)" type="button" class="btn btn-sm btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                          <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                        </svg>
                        Eliminar
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div v-if="userStore.formOrganizationRol.selectedOrganizationRol.length === 0" class="text-center mt-4 text-gray-500">
                No hay registros disponibles.
              </div>
            </div>      
            <button type="submit" class="btn btn-primary">Guardar</button>
          </form>
        </div>
      </div>
    </div>
  </template>  
  <script>
  import { ref, computed,onMounted } from 'vue';
  import axios from "axios";
  import { storeToRefs } from 'pinia';
  import { useUser } from '@/stores/modules/user';
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  import ErrorDisplay from '@/components/ErrorDisplay.vue';
  export default {
    components: {
      ErrorDisplay,
    },
    setup() {
      const userStore = useUser();
      const errorStore = useErrorMessages();
      async function getOrganizationRol(organization_id) { 
        await userStore.getRoles(organization_id);
      }
      async function addOrganizationRol() { 
         userStore.setOrganizationRol();
      }
      async function removeOrganizationRol(id,organization_id) { 
        userStore.deleteOrganizationRol(id,organization_id);
      }
      async function save() {
        try {
          await userStore.sendOrganizationRol();
          errorStore.setSuccess();
        } catch (error) {
          errorStore.setError(error); 
        } 
      }
      return { userStore,errorStore,save,getOrganizationRol,addOrganizationRol,removeOrganizationRol};
    },
  };
  </script>
