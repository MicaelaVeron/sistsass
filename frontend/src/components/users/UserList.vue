<template>
  <div class="page-container">
    <div class="page-header">
      <h1 class="text-gradient">Gestión de Usuarios</h1>
      <ActionButton 
        variant="primary" 
        icon="bi bi-plus-circle"
        @click="addUser()"
        data-bs-toggle="modal" 
        data-bs-target="#modalAgregar"
      >
        Nuevo Usuario
      </ActionButton>
    </div>

    <ErrorDisplay v-if="errorStore.componentType == 1" />

    <!-- Grid de usuarios -->
    <div v-if="userStore.users.length > 0" class="users-grid">
      <DataCard v-for="(user, index) in userStore.users" :key="user.id">
        <div class="user-card-content">
          <!-- Avatar y nombre -->
          <div class="user-header">
            <div class="user-avatar">
              <i class="bi bi-person-circle"></i>
            </div>
            <div class="user-info">
              <h3 class="user-name">{{ user.name }}</h3>
              <p class="user-email">
                <i class="bi bi-envelope"></i>
                {{ user.email }}
              </p>
            </div>
            <StatusBadge 
              :variant="user.status === 'active' ? 'success' : 'danger'"
              :icon="user.status === 'active' ? 'bi bi-check-circle' : 'bi bi-x-circle'"
            >
              {{ user.status === 'active' ? 'Activo' : 'Inactivo' }}
            </StatusBadge>
          </div>

          <!-- Acciones -->
          <div class="user-actions">
            <ActionButton 
              variant="primary" 
              size="sm"
              icon="bi bi-pencil-square"
              @click="editUser(user)"
              data-bs-toggle="modal" 
              title="Editar"
            >
            </ActionButton>

            <ActionButton 
              variant="warning"
              icon="bi bi-diagram-3"
              @click="addOrganizationRol(user)"
              title="Organizaciones y Roles"
            ></ActionButton>

            <ActionButton 
              variant="danger" 
              icon="bi bi-shop"
              @click="addBranches(user)"
              title="Sucursales"
            ></ActionButton>

            <ActionButton 
              v-if="user.status == 'active'"
              variant="danger" 
              size="sm"
              icon="bi bi-x-circle"
              @click="activeInactive(user, 'delete')"
              title="Desactivar"
            >
            </ActionButton>

            <ActionButton 
              v-else
              variant="success" 
              size="sm"
              icon="bi bi-check-circle"
              @click="activeInactive(user, 'active')"
               title="Activar"
            >
            </ActionButton>
          </div>
        </div>
      </DataCard>
    </div>

    <!-- Estado vacío -->
    <div v-else class="empty-state glass-panel">
      <i class="bi bi-people empty-icon"></i>
      <h3>No hay usuarios registrados</h3>
      <p>Comienza agregando tu primer usuario</p>
      <ActionButton 
        variant="primary" 
        icon="bi bi-plus-circle"
        @click="addUser()"
      >
        Agregar Usuario
      </ActionButton>
    </div>

    <!-- Modales -->
    <UserForm ref="userFormComp" />
    <UserFormOrganizationRol ref="userFormOrgRoleComp" />
    <UserFormBranch ref="userFormBranchComp" />
    <ConfirmModal 
      ref="confirmModal"
      :title="confirmModalData.title"
      :message="confirmModalData.message"
      :variant="confirmModalData.variant"
      @confirm="handleConfirm"
    />
  </div>
</template>

<script>
import { onMounted, ref } from 'vue'
import { useUser } from '@/stores/modules/user';
import UserForm from '@/components/users/UserForm.vue';
import UserFormOrganizationRol from '@/components/users/UserFormOrganizationRol.vue';
import UserFormBranch from '@/components/users/UserFormBranch.vue';
import { useErrorMessages } from '@/stores/modules/errors_messages';
import ErrorDisplay from '@/components/ErrorDisplay.vue';
import DataCard from '@/components/shared/DataCard.vue';
import ActionButton from '@/components/shared/ActionButton.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';

export default {
  components: {
    UserForm,
    UserFormOrganizationRol,
    UserFormBranch,
    ErrorDisplay,
    DataCard,
    ActionButton,
    StatusBadge,
    ConfirmModal
  },
  setup() {
    const errorStore = useErrorMessages();
    const userStore = useUser();
    let successTimeout = null;

    const userFormComp = ref(null);
    const userFormOrgRoleComp = ref(null);
    const userFormBranchComp = ref(null);

    const addUser = async () => {
      userStore.reset(); 
      userStore.setOperationType('create');
      if(userFormComp.value) userFormComp.value.show();
    };

    const editUser = async (user) => {
      await userStore.edit(user);
      userStore.setOperationType('update');
      if(userFormComp.value) userFormComp.value.show();
    };

    const confirmModal = ref(null);
    const confirmModalData = ref({
      title: 'Confirmar Acción',
      message: '',
      variant: 'danger',
      action: null
    });

    const handleConfirm = async () => {
        if (confirmModalData.value.action) {
            await confirmModalData.value.action();
            userStore.fetchUsers();
        }
    };

    const activeInactive = async (user, type) => {
      if (type === 'delete') {
         confirmModalData.value = {
            title: 'Desactivar Usuario',
            message: `¿Estás seguro de que deseas desactivar a ${user.name}?`,
            variant: 'danger',
            action: async () => {
               const isSuccess = await userStore.inactive({id: user.id});
               if(isSuccess) {
                  errorStore.setSuccessWithMessage('Usuario desactivado con éxito.');
                  clearTimeout(successTimeout);
                  successTimeout = setTimeout(() => {
                      errorStore.clearSuccess();
                  }, 5000);
               }
            }
         };
         confirmModal.value.show();
      } else {
         confirmModalData.value = {
            title: 'Activar Usuario',
            message: `¿Estás seguro de que deseas activar a ${user.name}?`,
            variant: 'success',
            action: async () => {
               const isSuccess = await userStore.active({id: user.id});
               if(isSuccess) {
                  errorStore.setSuccessWithMessage('Usuario activado con éxito.');
                  clearTimeout(successTimeout);
                  successTimeout = setTimeout(() => {
                      errorStore.clearSuccess();
                  }, 5000);
               }
            }
         };
         confirmModal.value.show();
      }
    };
    
    const addOrganizationRol = async (user) => {
      userStore.setUser(user.id);
      userStore.resetAddOrganizationRol(); 
      if(userFormOrgRoleComp.value) userFormOrgRoleComp.value.show();
      await userStore.getOrganizations();
      await userStore.getOrganizationAndRolesWithUser(user.id);
    };

    const addBranches = async (user) => {
      userStore.setUser(user.id);
      userStore.resetBranchList();
      userStore.setOperationType('create');
      if(userFormBranchComp.value) userFormBranchComp.value.show();
      await userStore.getBranches(user.id);
      await userStore.getBranchWithUser(user.id);
    };

    onMounted(() => {
      userStore.fetchUsers();
    });

    return { 
      userStore, 
      errorStore,
      addUser,
      editUser,
      addOrganizationRol,
      addBranches, 
      activeInactive,
      confirmModal,
      confirmModalData,
      handleConfirm,
      userFormComp,
      userFormOrgRoleComp,
      userFormBranchComp
    };
  }
};
</script>

<style scoped>
.page-container {
  padding: 2rem;
  max-width: 1400px;
  margin: 0 auto;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.page-header h1 {
  font-size: 2rem;
  margin: 0;
}

/* Grid de usuarios */
.users-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
}

/* Card de usuario */
.user-card-content {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.user-header {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
}

.user-avatar {
  flex-shrink: 0;
}

.user-avatar i {
  font-size: 3rem;
  color: var(--primary);
}

.user-info {
  flex: 1;
  min-width: 0;
}

.user-name {
  font-size: 1.2rem;
  font-weight: 700;
  margin: 0 0 0.5rem 0;
  color: var(--text-main);
}

.user-email {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--text-muted);
  font-size: 0.9rem;
  margin: 0;
}

.user-email i {
  font-size: 0.85rem;
}

/* Acciones */
.user-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

/* Estado vacío */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.empty-icon {
  font-size: 4rem;
  color: var(--text-muted);
  opacity: 0.5;
}

.empty-state h3 {
  color: var(--text-main);
  margin: 0;
}

.empty-state p {
  color: var(--text-muted);
  margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
  .page-container {
    padding: 1rem;
  }

  .page-header {
    flex-direction: column;
    align-items: stretch;
  }

  .users-grid {
    grid-template-columns: 1fr;
  }

  .user-actions {
    flex-direction: column;
  }
}
</style>