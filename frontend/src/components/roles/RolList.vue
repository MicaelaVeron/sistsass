<template>
    <div class="container-fluid d-flex flex-column mb-3 justify-content-center align-items-center p-5">
      <h1 class="text-2xl p-5 font-bold">Roles</h1>
      <div class="mb-4">
        <button @click="addRol()"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
          </svg>
            Agregar
        </button>
      </div>

      <ErrorDisplay v-if="errorStore.componentType == 1" />
      <div class="table-responsive col-md-8">
        <table class="table">
          <thead class="table-dark">
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Nombre</th>
              <th class="text-center">Guard Name</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(rol,index) in rolStore.roles" :key="rol.id">
              <td class="text-center" >{{ index + 1 }}</td>
              <td class="text-center" >{{ rol.name }}</td>
              <td class="text-center" >{{ rol.guard_name }}</td>
              <td class="text-center">
                <button @click="editRol(rol)" data-bs-toggle="modal" data-bs-target="#modalAgregar"  type="button" class="btn btn-sm btn-primary">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                  </svg>
                  Editar
                </button>
                <button @click="deleteRol(rol)" type="button" class="btn btn-sm btn-danger">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                  </svg>
                  Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="rolStore.roles.length === 0" class="text-center mt-4 text-gray-500">
          No hay roles disponibles.
        </div>
      </div>
      <div class="modal fade" 
            id="modalAgregar" 
            tabindex="-1" 
            role="dialog" 
            aria-labelledby="modalAgregar" 
            aria-hidden="true">
            <agregar></agregar>
		</div>
    </div>
  </template>
  
  <script>
  import {ref, onMounted } from 'vue'
  import { useRol } from '@/stores/modules/rol';
  import RolForm from '@/components/roles/RolForm.vue';
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  import ErrorDisplay from '@/components/ErrorDisplay.vue';
  export default {
    components: {
      ErrorDisplay,
      'agregar': RolForm,
      
    },
    setup() {
  
      const errorStore = useErrorMessages();
      const rolStore = useRol();
      const addRol = async () => {
        rolStore.reset(); 
        errorStore.clearErrors();
        rolStore.setOperationType('create');
      };
      const editRol = async (rol) => {
        errorStore.clearErrors();
        rolStore.edit(rol); 
        rolStore.setOperationType('update');
      };
      const deleteRol = async (rol) => {
        errorStore.clearErrors();
        const confirmation = confirm("¿Estás seguro de que deseas eliminar esta sucursal?");
        if (confirmation) {
          try {
            await rolStore.delete(rol);
            errorStore.setSuccess();
            errorStore.setComponentType(1);
          } catch (error) {
            errorStore.setError(error);
            errorStore.setComponentType(1);
          }
        }
      };
      onMounted(() => {
        rolStore.fetchRoles(); 
      });
      return { rolStore, errorStore, addRol, editRol, deleteRol  };
    }
  };
</script>
  