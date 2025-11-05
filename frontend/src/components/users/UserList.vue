<template>
    <div class="container-fluid d-flex flex-column mb-3 justify-content-center align-items-center p-5">
      <h1 class="text-2xl p-5 font-bold">Usuarios</h1>
      <div class="mb-4">
        <button @click="addUser()"  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
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
              <th class="text-center">Correo</th>
              <th class="text-center">Estado</th>
              <th class="text-center">Acciones</th>
              
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user,index) in userStore.users" :key="user.id">
              <td class="text-center" >{{ index + 1 }}</td>
              <td class="text-center" >{{ user.name }}</td>
              <td class="text-center" >{{ user.email }}</td>
              <td class="text-center" >{{ (user.status == 'active') ? 'Activo' : 'Desactivado' }}</td>
              <td class="text-center">
                <button @click="editUser(user)" data-bs-toggle="modal" data-bs-target="#modalAgregar"  type="button" class="btn btn-sm btn-primary">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                  </svg>
                  Editar
                </button>
                <button  @click="addOrganizationRol(user)"  data-bs-toggle="modal" data-bs-target="#modalOrganizationRol" type="button" class="btn btn-sm btn-warning">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-handbag-fill" viewBox="0 0 16 16">
                    <path d="M8 1a2 2 0 0 0-2 2v2H5V3a3 3 0 1 1 6 0v2h-1V3a2 2 0 0 0-2-2M5 5H3.36a1.5 1.5 0 0 0-1.483 1.277L.85 13.13A2.5 2.5 0 0 0 3.322 16h9.355a2.5 2.5 0 0 0 2.473-2.87l-1.028-6.853A1.5 1.5 0 0 0 12.64 5H11v1.5a.5.5 0 0 1-1 0V5H6v1.5a.5.5 0 0 1-1 0z"/>
                  </svg>
                  Organizaciones y Roles
                </button>
                <button  @click="addOrganizationBranch(user)"  data-bs-toggle="modal" data-bs-target="#modalOrganizationBranch" type="button" class="btn btn-sm btn-success">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-add" viewBox="0 0 16 16">
                  <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h4a.5.5 0 1 0 0-1h-4a.5.5 0 0 1-.5-.5V7.207l5-5 6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                  <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 1 0 1 0v-1h1a.5.5 0 1 0 0-1h-1v-1a.5.5 0 0 0-.5-.5"/>
                </svg>
                  Sucursales
                </button>
                <button @click="deleteUser(user)" type="button" class="btn btn-sm btn-danger">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                  </svg>
                  Desactivar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="userStore.users.length === 0" class="text-center mt-4 text-gray-500">
          No hay registros disponibles.
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
      <div class="modal fade" 
            id="modalOrganizationRol" 
            tabindex="-1" 
            role="dialog" 
            aria-labelledby="modalOrganizationRol" 
            aria-hidden="true">
            <agregarOrganizationRol></agregarOrganizationRol>
		  </div>
      <div class="modal fade" 
            id="modalOrganizationBranch" 
            tabindex="-1" 
            role="dialog" 
            aria-labelledby="modalOrganizationBranch" 
            aria-hidden="true">
            <agregarOrganizationBranch></agregarOrganizationBranch>
		  </div>
    </div>
  </template>
  
  <script>
  import {ref, onMounted } from 'vue'
  import { useUser } from '@/stores/modules/user';
  import UserForm from '@/components/users/UserForm.vue';
  import UserFormOrganizationRol from '@/components/users/UserFormOrganizationRol.vue';
  import UserFormBranchOrganization from '@/components/users/UserFormBranchOrganization.vue';
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  import ErrorDisplay from '@/components/ErrorDisplay.vue';
  export default {
    components: {
      ErrorDisplay,
      'agregar': UserForm,
      'agregarOrganizationRol': UserFormOrganizationRol,
      'agregarOrganizationBranch': UserFormBranchOrganization,
      
    },
    setup() {
  
      const errorStore = useErrorMessages();
      const userStore = useUser();
      const addUser = async () => {
        userStore.reset(); 
        errorStore.clearErrors();
        userStore.setOperationType('create');
      };
      const editUser = async (user) => {
        errorStore.clearErrors();
        userStore.edit(user); 
        userStore.setOperationType('update');
      };
      const deleteUser = async (user) => {
        errorStore.clearErrors();
        const confirmation = confirm("¿Estás seguro de que deseas eliminar este registro?");
        if (confirmation) {
          try {
            await userStore.delete(user);
            errorStore.setSuccess();
            errorStore.setComponentType(1);
          } catch (error) {
            errorStore.setError(error);
            errorStore.setComponentType(1);
          }
        }
      };
      const addOrganizationRol = async (user) => {
        userStore.setUser(user.id);
        userStore.resetAddOrganizationRol(); 
        errorStore.clearErrors();
        userStore.setOperationType('create');
        await userStore.getOrganizations();
        await userStore.getOrganizationAndRolesWithUser(user.id);
      };
      const addOrganizationBranch = async (user) => {
        userStore.setUser(user.id);
        userStore.resetAddOrganizationBranch(); 
        errorStore.clearErrors();
        userStore.setOperationType('create');
        await userStore.getUserOrganizations(user.id);
      };
      onMounted(() => {
        userStore.fetchUsers(); // Llama a fetchMenus cuando el componente se monta
      });
      return { userStore, errorStore,addUser,editUser,deleteUser,addOrganizationRol,addOrganizationBranch };
    }
  };
</script>
  