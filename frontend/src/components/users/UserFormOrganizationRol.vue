<template>
  <Teleport to="body">
    <div v-if="isVisible" class="modal-backdrop-venom">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-panel modal-venom">
          <div class="modal-header-venom">
            <h5 class="modal-title-venom">
              <i class="bi bi-diagram-3-fill"></i>
              Asignar Organizaciones y Roles
            </h5>
            <button type="button" class="btn-close-venom" @click="closeModal" aria-label="Close">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
          
          <div class="modal-body-venom">
            <ErrorDisplay />
            
            <div class="assignment-container">
              <SearchInput 
                v-model="searchQuery" 
                placeholder="Buscar organización..." 
              />
              
              <div class="organizations-list custom-scrollbar">
                <div v-if="filteredOrganizations.length === 0" class="empty-state">
                  <i class="bi bi-search"></i>
                  <p>No se encontraron organizaciones</p>
                </div>
                
                <CollapsibleSection
                  v-for="org in filteredOrganizations"
                  :key="org.id"
                  :title="org.name"
                  icon="bi bi-building"
                  :badge="getSelectedRolesCountForOrg(org.id) || null"
                  :defaultOpen="getSelectedRolesCountForOrg(org.id) > 0"
                >
                  <div v-if="loadingRoles[org.id]" class="loading-state">
                    <div class="spinner-border text-primary spinner-sm" role="status">
                      <span class="visually-hidden">Cargando roles...</span>
                    </div>
                  </div>
                  <div v-else class="roles-grid">
                    <CheckboxVenom
                      v-for="rol in getRolesForOrg(org.id)"
                      :key="rol.id"
                      :modelValue="isRoleSelected(org.id, rol.id)"
                      @update:modelValue="toggleRole(org.id, rol.id, $event)"
                    >
                      {{ rol.name }}
                    </CheckboxVenom>
                    <div v-if="getRolesForOrg(org.id).length === 0" class="text-muted small">
                      No hay roles disponibles en esta organización.
                    </div>
                  </div>
                </CollapsibleSection>
              </div>
            </div>

            <div class="form-actions">
              <ActionButton 
                type="button"
                variant="secondary" 
                @click="closeModal"
              >
                Cancelar
              </ActionButton>
              <ActionButton 
                type="button"
                variant="primary"
                @click="save"
                :disabled="errorStore.status !== null"
              >
                <i class="bi bi-check-circle"></i>
                Guardar Asignaciones
              </ActionButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script>
import { ref, computed, watch } from 'vue';
import { useUser } from '@/stores/modules/user';
import { useErrorMessages } from '@/stores/modules/errors_messages';
import ErrorDisplay from '@/components/ErrorDisplay.vue';
import SearchInput from '@/components/shared/SearchInput.vue';
import CollapsibleSection from '@/components/shared/CollapsibleSection.vue';
import CheckboxVenom from '@/components/shared/CheckboxVenom.vue';
import ActionButton from '@/components/shared/ActionButton.vue';
import axios from '@/axios';

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
    
    const orgRoles = ref({});
    const loadingRoles = ref({});

    const loadAllRoles = async () => {
       if (!userStore.organizations || userStore.organizations.length === 0) return;
       for (const org of userStore.organizations) {
           if (!orgRoles.value[org.id] && !loadingRoles.value[org.id]) {
               loadingRoles.value[org.id] = true;
               try {
                  const response = await axios.get(`/api/organization-rol-getRolWithOrganization/${org.id}`);
                  orgRoles.value[org.id] = response.data;
               } catch (e) {
                  console.error(e);
               } finally {
                  loadingRoles.value[org.id] = false;
               }
           }
       }
    };

    watch(
       () => [userStore.formOrganizationRol.user_id, userStore.organizations],
       ([newUserId, orgs]) => {
          if (newUserId && orgs && orgs.length > 0) {
             loadAllRoles();
          }
       },
       { immediate: true, deep: true }
    );

    const filteredOrganizations = computed(() => {
      if (!searchQuery.value) return userStore.organizations;
      const lowerQuery = searchQuery.value.toLowerCase();
      return userStore.organizations.filter(org => 
        org.name.toLowerCase().includes(lowerQuery)
      );
    });

    const getRolesForOrg = (orgId) => {
       return orgRoles.value[orgId] || [];
    };

    const isRoleSelected = (orgId, rolId) => {
       const selections = userStore.formOrganizationRol.selectedOrganizationRol;
       return selections.some(s => s.organization_id === orgId && s.rol_id === rolId);
    };

    const getSelectedRolesCountForOrg = (orgId) => {
       const selections = userStore.formOrganizationRol.selectedOrganizationRol;
       return selections.filter(s => s.organization_id === orgId).length;
    };

    const toggleRole = (orgId, rolId, isChecked) => {
        if (isChecked) {
             const org = userStore.organizations.find(o => o.id == orgId);
             const rol = orgRoles.value[orgId].find(r => r.id == rolId);
             
             userStore.formOrganizationRol.selectedOrganizationRol.push({
                 id: Date.now(),
                 organization_id: orgId,
                 rol_id: rolId,
                 organization: { name: org ? org.name : '' },
                 rol: { name: rol ? rol.name : '' }
             });
             
             userStore.organization_id = orgId;
             userStore.rol_id = rolId;
        } else {
             const sel = userStore.formOrganizationRol.selectedOrganizationRol.find(
                 s => s.organization_id == orgId && s.rol_id == rolId
             );
             if (sel) {
                 userStore.deleteOrganizationRol(sel.id, orgId);
                 userStore.formOrganizationRol.selectedOrganizationRol = 
                   userStore.formOrganizationRol.selectedOrganizationRol.filter(s => s !== sel);
             }
        }
    };

    const save = async () => {
      try {
        await userStore.sendOrganizationRol();
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
      filteredOrganizations,
      getRolesForOrg,
      loadingRoles,
      isRoleSelected,
      getSelectedRolesCountForOrg,
      toggleRole,
      save,
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

.modal-lg {
  max-width: 800px;
  width: 90vw;
}

.modal-venom {
  background: var(--bg-card);
  border: 1px solid var(--glass-border);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
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
  color: var(--warning);
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

.organizations-list {
  max-height: 400px;
  overflow-y: auto;
  padding-right: 0.5rem;
}

.roles-grid {
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

.spinner-sm {
  width: 1.5rem;
  height: 1.5rem;
  border-width: 0.2em;
}
.loading-state {
  display:flex; 
  align-items:center; 
  gap: 0.5rem; 
  color: var(--text-muted);
}

/* Custom Scrollbar for inner list */
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
  .roles-grid {
    grid-template-columns: 1fr;
  }
}
@keyframes zoomIn {
  from { transform: scale(0.8); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
</style>
