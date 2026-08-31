<template>
  <div class="page-container">
    <div class="page-header">
      <h1 class="text-gradient">Gestión de Roles</h1>
      <ActionButton 
        variant="primary" 
        icon="bi bi-plus-circle"
        @click="addRol()"
        data-bs-toggle="modal" 
        data-bs-target="#modalAgregar"
      >
        Nuevo Rol
      </ActionButton>
    </div>

    <ErrorDisplay v-if="errorStore.componentType == 1" />

    <!-- Grid de roles -->
    <div v-if="rolStore.roles.length > 0" class="roles-grid">
      <DataCard v-for="(rol, index) in rolStore.roles" :key="rol.id">
        <div class="rol-card-content">
          <!-- Header del rol -->
          <div class="rol-header">
            <div class="rol-icon">
              <i class="bi bi-shield-lock"></i>
            </div>
            <div class="rol-info">
              <h3 class="rol-name">{{ rol.name }}</h3>
              <StatusBadge variant="info" icon="bi bi-shield">
                {{ rol.guard_name }}
              </StatusBadge>
            </div>
          </div>

          <!-- Acciones -->
          <div class="rol-actions">
            <ActionButton 
              variant="primary" 
              icon="bi bi-pencil-square"
              @click="editRol(rol)"
              title="Editar"
            ></ActionButton>

            <ActionButton 
              variant="danger" 
              size="sm"
              icon="bi bi-trash"
              @click="deleteRol(rol)"
              title="Eliminar"
            >
            </ActionButton>
          </div>
        </div>
      </DataCard>
    </div>

    <!-- Estado vacío -->
    <div v-else class="empty-state glass-panel">
      <i class="bi bi-shield-lock empty-icon"></i>
      <h3>No hay roles registrados</h3>
      <p>Comienza agregando tu primer rol</p>
      <ActionButton 
        variant="primary" 
        icon="bi bi-plus-circle"
        @click="addRol()"
      >
        Agregar Rol
      </ActionButton>
    </div>

    <!-- Modales -->
    <RolForm ref="rolFormComp" />
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
import { useRol } from '@/stores/modules/rol';
import RolForm from '@/components/roles/RolForm.vue';
import { useErrorMessages } from '@/stores/modules/errors_messages';
import ErrorDisplay from '@/components/ErrorDisplay.vue';
import DataCard from '@/components/shared/DataCard.vue';
import ActionButton from '@/components/shared/ActionButton.vue';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import ConfirmModal from '@/components/shared/ConfirmModal.vue';

export default {
  components: {
    ErrorDisplay,
    DataCard,
    ActionButton,
    StatusBadge,
    RolForm,
    ConfirmModal
  },
  setup() {
    const errorStore = useErrorMessages();
    const rolStore = useRol();
    const rolFormComp = ref(null);

    const addRol = async () => {
      rolStore.reset(); 
      errorStore.clearErrors();
      rolStore.setOperationType('create');
      if (rolFormComp.value) rolFormComp.value.show();
    };

    const editRol = async (rol) => {
      errorStore.clearErrors();
      await rolStore.edit(rol); 
      rolStore.setOperationType('update');
      if (rolFormComp.value) rolFormComp.value.show();
    };

    const confirmModal = ref(null);
    const confirmModalData = ref({
      title: '',
      message: '',
      variant: 'danger',
      action: null
    });

    const handleConfirm = async () => {
        if (confirmModalData.value.action) {
            await confirmModalData.value.action();
        }
    };

    const deleteRol = async (rol) => {
      errorStore.clearErrors();
      confirmModalData.value = {
        title: 'Eliminar Rol',
        message: `¿Estás seguro de que deseas eliminar el rol ${rol.name}?`,
        variant: 'danger',
        action: async () => {
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
      confirmModal.value.show();
    };

    onMounted(() => {
      rolStore.fetchRoles(); 
    });

    return { 
      rolStore, 
      errorStore, 
      addRol, 
      editRol, 
      deleteRol,
      rolFormComp,
      confirmModal,
      confirmModalData,
      handleConfirm
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

/* Grid de roles */
.roles-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1.5rem;
}

/* Card de rol */
.rol-card-content {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.rol-header {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
}

.rol-icon {
  flex-shrink: 0;
}

.rol-icon i {
  font-size: 2.5rem;
  color: var(--secondary);
}

.rol-info {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.rol-name {
  font-size: 1.3rem;
  font-weight: 700;
  margin: 0;
  color: var(--text-main);
  text-transform: capitalize;
}

/* Acciones */
.rol-actions {
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

  .roles-grid {
    grid-template-columns: 1fr;
  }

  .rol-actions {
    flex-direction: column;
  }
}
</style>