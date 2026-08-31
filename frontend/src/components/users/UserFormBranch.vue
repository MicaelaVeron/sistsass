<template>
  <Teleport to="body">
    <div v-if="isVisible" class="modal-backdrop-venom">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-panel modal-venom">
          <div class="modal-header-venom">
            <h5 class="modal-title-venom">
              <i class="bi bi-shop"></i>
              Asignar Sucursales
            </h5>
            <button type="button" class="btn-close-venom" @click="closeModal" aria-label="Close">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
          
          <div class="modal-body-venom">
            <ErrorDisplay />
            
            <form @submit.prevent="handleSubmit">
              <div class="assignment-container">
                <SearchInput 
                  v-model="searchQuery" 
                  placeholder="Buscar sucursal u organización..." 
                />
                
                <div class="branches-list custom-scrollbar">
                  <div v-if="Object.keys(groupedFilteredBranches).length === 0" class="empty-state">
                    <i class="bi bi-search"></i>
                    <p>No se encontraron sucursales</p>
                  </div>
                  
                  <CollapsibleSection
                    v-for="(branches, orgId) in groupedFilteredBranches"
                    :key="orgId"
                    :title="getOrganizationName(orgId)"
                    icon="bi bi-building"
                    :badge="getSelectedBranchesCountForOrg(orgId) || null"
                    :defaultOpen="getSelectedBranchesCountForOrg(orgId) > 0 || searchQuery !== ''"
                  >
                    <div class="branches-grid">
                      <CheckboxVenom
                        v-for="branch in branches"
                        :key="branch.id"
                        :modelValue="isBranchSelected(branch.id)"
                        @update:modelValue="toggleBranch(branch.id)"
                      >
                        {{ branch.name }}
                      </CheckboxVenom>
                    </div>
                  </CollapsibleSection>
                </div>
              </div>
              
              <div class="form-actions mt-4">
                <ActionButton 
                  type="button" 
                  variant="secondary" 
                  @click="closeModal"
                >
                  Cancelar
                </ActionButton>
                <ActionButton 
                  type="submit" 
                  variant="primary" 
                  :disabled="errorStore.status !== null"
                >
                  <i class="bi bi-check-circle"></i> 
                  {{ userStore.operationType === 'create' ? 'Crear y Continuar' : 'Guardar Cambios' }}
                </ActionButton>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script>
import { ref, computed } from 'vue';
import { useUser } from '@/stores/modules/user';
import { useErrorMessages } from '@/stores/modules/errors_messages';
import ErrorDisplay from '@/components/ErrorDisplay.vue';
import SearchInput from '@/components/shared/SearchInput.vue';
import CollapsibleSection from '@/components/shared/CollapsibleSection.vue';
import CheckboxVenom from '@/components/shared/CheckboxVenom.vue';
import ActionButton from '@/components/shared/ActionButton.vue';

export default {
  components: {
    ErrorDisplay,
    SearchInput,
    CollapsibleSection,
    CheckboxVenom,
    ActionButton
  },
  setup() {
    const userStore = useUser();
    const errorStore = useErrorMessages();
    const searchQuery = ref('');
    const isVisible = ref(false);

    const show = () => isVisible.value = true;
    const hide = () => {
       isVisible.value = false;
    };

    const getOrganizationName = (orgId) => {
      const org = userStore.organizations.find(o => o.id == orgId);
      return org ? org.name : `Organización #${orgId}`;
    };

    const groupedFilteredBranches = computed(() => {
      const grouped = {};
      const lowerQuery = searchQuery.value.toLowerCase();
      
      userStore.branches.forEach(branch => {
        const orgId = branch.organization_id || branch.organization?.id;
        const orgName = getOrganizationName(orgId).toLowerCase();
        const branchName = (branch.name || '').toLowerCase();
        
        if (!searchQuery.value || branchName.includes(lowerQuery) || orgName.includes(lowerQuery)) {
          if (!grouped[orgId]) {
            grouped[orgId] = [];
          }
          grouped[orgId].push(branch);
        }
      });
      
      return grouped;
    });

    const isBranchSelected = (branchId) => {
      return userStore.formBranch.selectedBranch.includes(branchId);
    };

    const getSelectedBranchesCountForOrg = (orgId) => {
       return userStore.formBranch.selectedBranch.filter(branchId => {
           const branch = userStore.branches.find(b => b.id === branchId);
           const bOrgId = branch?.organization_id || branch?.organization?.id;
           return bOrgId == orgId;
       }).length;
    };

    const toggleBranch = (branchId) => {
      userStore.setBranches(branchId);
    };

    const handleSubmit = async () => {
      try {
        await userStore.sendBranch();
        errorStore.setSuccess();
        setTimeout(() => {
          hide();
        }, 1000);
      } catch (error) {
        errorStore.setError(error); 
      } 
    };

    const closeModal = () => {
      errorStore.clearErrors();
      searchQuery.value = '';
      hide();
    };

    return { 
      userStore,
      errorStore,
      searchQuery,
      groupedFilteredBranches,
      getOrganizationName,
      isBranchSelected,
      getSelectedBranchesCountForOrg,
      toggleBranch,
      handleSubmit,
      closeModal,
      isVisible,
      show,
      hide
    };
  },
};
</script>

<style scoped>
.modal-backdrop-venom {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(5px);
  z-index: 1055;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-venom {
  background: var(--bg-card);
  border: 1px solid var(--glass-border);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
  width: 90vw;
  max-width: 800px;
  max-height: 90vh;
  overflow-y: auto;
  animation: zoomIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.modal-header-venom {
  padding: 1.5rem;
  border-bottom: 1px solid var(--glass-border);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-title-venom {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--text-main);
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin: 0;
}

.modal-title-venom i {
  color: var(--success);
  font-size: 1.5rem;
}

.btn-close-venom {
  background: transparent;
  border: 1px solid var(--glass-border);
  color: var(--text-muted);
  width: 2.5rem;
  height: 2.5rem;
  border-radius: var(--radius-sm);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: var(--transition-fast);
}

.btn-close-venom:hover {
  border-color: #ff4d4d;
  color: #ff4d4d;
  background: rgba(255, 77, 77, 0.1);
}

.modal-body-venom {
  padding: 2rem 1.5rem;
}

.assignment-container {
  background: rgba(0, 0, 0, 0.2);
  border-radius: var(--radius-md);
  padding: 1rem;
  border: 1px solid var(--glass-border);
}

.branches-list {
  max-height: 400px;
  overflow-y: auto;
  padding-right: 0.5rem;
}

.branches-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
}

.empty-state {
  text-align: center;
  padding: 2rem;
  color: var(--text-muted);
}

.empty-state i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  display: block;
}

.form-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1.5rem;
  justify-content: flex-end;
}

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.3);
}

/* Responsive */
@media (max-width: 576px) {
  .branches-grid {
    grid-template-columns: 1fr;
  }
}
@keyframes zoomIn {
  from { transform: scale(0.8); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
</style>