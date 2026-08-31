<template>
    <div class="selector-page">
      <div class="selector-card glass-panel">
        <h2 class="text-gradient text-center mb-4">Selecciona tu Espacio</h2>
        
        <div class="form-group mb-4">
          <label class="form-label">Organización</label>
          <CustomSelect
            v-model="selectedOrg"
            :options="organizations"
            placeholder="Seleccione una organización"
            @change="getOrganizationRol"
            labelKey="name"
            valueKey="id"
          >
            <template #icon>
                <i class="bi bi-building select-icon-slot"></i>
            </template>
          </CustomSelect>
        </div>
  
        <div class="form-group mb-4" :class="{ 'disabled': !selectedOrg }">
          <label class="form-label">Rol</label>
          <CustomSelect
            v-model="selectedOrganizationRole"
            :options="formattedRoles"
            placeholder="Seleccione un rol"
            :disabled="!selectedOrg"
            labelKey="name"
            valueKey="id"
          >
             <template #icon>
                <i class="bi bi-person-badge select-icon-slot"></i>
            </template>
          </CustomSelect>
        </div>
  
        <div class="d-grid mt-5">
           <button class="btn-venom" @click="confirmSelection">
             <span v-if="!isLoading">Ingresar al Dashboard</span>
             <span v-else>Cargando...</span>
           </button>
           <p v-if="errorMessage" class="error-text text-center mt-3">{{ errorMessage }}</p>
        </div>

      </div>
    </div>
</template>
  
<script>
import { computed, ref } from 'vue'
import { useAuthStore } from '@/stores/modules/auth'
import { useRouter } from 'vue-router'
import CustomSelect from './CustomSelect.vue'

export default {
    components: { CustomSelect },
    setup() {
      const authStore = useAuthStore()
      const router = useRouter();
      const isLoading = ref(false);
      
      const user = computed(() => authStore.user)
      const organizations = computed(() => authStore.authUserOrganizations || [])
  
      const selectedOrg = computed({
        get: () => authStore.activeOrganizationId,
        set: (val) => authStore.setActiveOrganization(val)
      })
  
      const OrganizationRol = computed(() => authStore.authOrganizationRol || [])
      
      // Helper to format roles for the select (extracting rol name)
      const formattedRoles = computed(() => {
          return OrganizationRol.value.map(or => ({
              id: or.id,
              name: or.rol ? or.rol.name : 'Unknown Role'
          }));
      });
  
      const selectedOrganizationRole = computed({
        get: () => authStore.activeOrganizationRoleId,
        set: async (val) => {
          await authStore.setActiveRole(val)
        }
      })
  
      const errorMessage = ref('');
      
      const confirmSelection = async () => {
        errorMessage.value = '';
        
        if (!selectedOrg.value) {
            errorMessage.value = "Por favor, selecciona una organización.";
            return;
        }
        
        if (!selectedOrganizationRole.value) {
            errorMessage.value = "Por favor, selecciona un rol.";
            return;
        }

        isLoading.value = true;
        try {
            authStore.lockSelections();
            // Validate context save before routing
            await authStore.updateUserContext();
            await authStore.getMenus();
            router.push('/dashboard');
        } catch(e) {
            console.error(e);
            errorMessage.value = "Error al cargar el menú.";
        } finally {
            isLoading.value = false;
        }
      }
  
      async function getOrganizationRol() { 
          errorMessage.value = ''; // Clear error on change
          await authStore.fetchOrganizationRol();
          
          // Logic: 
          // 1. If user has a last_role_id for this organization, try to select it.
          // 2. Else, select the first available role.
          
          let roleToSelect = null;
          
          if(user.value.last_role_id) {
             const found = OrganizationRol.value.find(r => r.id == user.value.last_role_id);
             if(found) roleToSelect = found.id;
          }

          if(!roleToSelect && OrganizationRol.value && OrganizationRol.value.length > 0) {
              roleToSelect = OrganizationRol.value[0].id;
          }
          
          if(roleToSelect) {
             selectedOrganizationRole.value = roleToSelect;
          }
      }

      // Initial Load: Check if user has last_organization_id
      // We need to do this on mount.
      if (!selectedOrg.value && organizations.value.length > 0) {
          let orgToSelect = null;
          
          if (user.value.last_organization_id) {
              const orgExists = organizations.value.find(o => o.id == user.value.last_organization_id);
              if (orgExists) {
                  orgToSelect = user.value.last_organization_id;
              }
          }
          
          // Fallback: If no last org or last org not found, select the first one
          if (!orgToSelect) {
              orgToSelect = organizations.value[0].id;
          }
          
          if (orgToSelect) {
              selectedOrg.value = orgToSelect;
              // Trigger fetch roles
              getOrganizationRol();
          }
      } else if (selectedOrg.value) {
          // If organization is already selected (e.g. coming from "Change Organization"), 
          // we still need to fetch roles and auto-select the current/last role.
          getOrganizationRol();
      }
  
      return {
        authStore,
        user,
        organizations,
        selectedOrg,
        OrganizationRol,
        formattedRoles,
        selectedOrganizationRole,
        confirmSelection,
        getOrganizationRol,
        router,
        isLoading,
        errorMessage
      }
    }
}
</script>

<style scoped>
.selector-page {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1rem;
    background: transparent;
}

.selector-card {
    width: 100%;
    max-width: 480px;
    padding: 3rem 2.5rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--glass-border);
    animation: fadeIn 0.8s ease-out;
}

.text-gradient {
    font-size: 1.8rem;
    margin-bottom: 2rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-muted);
    font-size: 0.9rem;
    font-weight: 500;
}

.select-icon-slot {
   color: var(--primary);
   font-size: 1.1rem;
   margin-right: 0.5rem;
}

.form-group.disabled {
    opacity: 0.5;
    /* Removed pointer-events: none to allow CustomSelect to handle cursor */
    cursor: not-allowed;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.error-text {
    color: #ff4d4d;
    font-size: 0.9rem;
    animation: shake 0.4s ease-in-out;
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}
</style>