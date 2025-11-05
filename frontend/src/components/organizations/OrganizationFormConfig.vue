<template>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Configuración</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ErrorDisplay  />
          <form @submit.prevent="save">
            <div class="mb-3">
              <label for="rol_id" class="form-label">Rol</label>
              <select
                id="rol_id"
                v-model="organizationStore.formConfig.rol_id"
                class="form-select"
                @change="getOrganizationRol($event.target.value)"
              >
              <option :value="null">Seleccione un Rol</option>
              <option
                v-for="rol in organizationStore.roles"
                :key="rol.id"
                :value="rol.id"
              >
                {{ rol.name }}
              </option>
              </select>
            </div>
            <hr v-if="organizationStore.formConfig.rol_id && organizationStore.permissions.length > 0" />
          <h5  v-if="organizationStore.formConfig.rol_id && organizationStore.permissions.length > 0" >Permisos</h5>
            <div class="form-check" v-for="permission in organizationStore.permissions" :key="permission.id">
            <input
              class="form-check-input"
              type="checkbox"
              :value="permission.id"
              :checked="organizationStore.formConfig.selectedPermissions.includes(permission.id)"
              @change="organizationStore.setPermissions(permission.id)"
              :id="'permission-' + permission.id"
            />
            <label class="form-check-label" :for="'permission-' + permission.id">
              {{ permission.name }}
            </label>
          </div>
          <hr v-if="organizationStore.formConfig.rol_id && organizationStore.menus.length > 0" />
          <h5 v-if="organizationStore.formConfig.rol_id && organizationStore.menus.length > 0">Menús</h5>
            <!-- Árbol de menús -->
            <MenuTree
              :menus="organizationStore.menus"
              :selectedMenus="organizationStore.formConfig.selectedMenus"
            />
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
  import { useOrganization } from '@/stores/modules/organization';
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  import ErrorDisplay from '@/components/ErrorDisplay.vue';
  import MenuTree from "@/components/menus/MenuTree.vue";
  export default {
    components: {
      ErrorDisplay,
      MenuTree,
    },
    setup() {
      const organizationStore = useOrganization();
      const errorStore = useErrorMessages();
      const { menus, selectedMenus, form } = storeToRefs(organizationStore);
      async function getOrganizationRol() { 
        await organizationStore.fetchDetalleRol();
      }
      async function save() {
        try {
          await organizationStore.sendConfig();
          errorStore.setSuccess();
        } catch (error) {
          errorStore.setError(error); 
        } 
      }
  
      return { save,organizationStore,errorStore,menus,selectedMenus,form,getOrganizationRol  };
    },
  };
  </script>
