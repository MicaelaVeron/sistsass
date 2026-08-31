<template>
  <Teleport to="body">
    <div v-if="isVisible" class="modal-backdrop-venom">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-panel modal-venom">
          <div class="modal-header-venom">
            <h5 class="modal-title-venom">
              <i class="bi bi-shield-lock"></i>
              {{ rolStore.operationType === 'create' ? 'Nuevo Rol' : 'Editar Rol' }}
            </h5>
            <button type="button" class="btn-close-venom" @click="closeModal" aria-label="Close">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
          
          <div class="modal-body-venom">
            <ErrorDisplay v-if="errorStore.componentType == 2" />
            
            <form @submit.prevent="handleSubmit" class="form-venom">
              <FormInput
                id="name"
                label="Nombre del Rol"
                type="text"
                v-model="rolStore.form.name"
                placeholder="Ej: Administrador, Editor, Visor"
                required
                hint="Nombre descriptivo del rol"
              />

              <FormInput
                id="guard_name"
                label="Guard Name"
                type="select"
                v-model="rolStore.form.guard_name"
                required
                hint="Tipo de autenticación para este rol"
              >
                <option
                  v-for="guard in rolStore.guards_name"
                  :key="guard.id"
                  :value="guard.value"
                >
                  {{ guard.value }}
                </option>
              </FormInput>

              <div class="form-actions">
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
                  {{ rolStore.operationType === 'create' ? 'Crear Rol' : 'Guardar Cambios' }}
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
import { ref } from 'vue';
import { useRol } from '@/stores/modules/rol';
import { useErrorMessages } from '@/stores/modules/errors_messages';
import ErrorDisplay from '@/components/ErrorDisplay.vue';
import FormInput from '@/components/shared/FormInput.vue';
import ActionButton from '@/components/shared/ActionButton.vue';

export default {
  components: {
    ErrorDisplay,
    FormInput,
    ActionButton
  },
  setup() {
    const rolStore = useRol();
    const errorStore = useErrorMessages();
    const isVisible = ref(false);

    const show = () => isVisible.value = true;
    const hide = () => {
       isVisible.value = false;
       closeModal();
    };

    const handleSubmit = async () => {
      errorStore.clearErrors();
      try {
        await rolStore.send();
        errorStore.setSuccess();
        errorStore.setComponentType(2);
        setTimeout(() => {
          hide();
        }, 1000);
      } catch (error) {
        errorStore.setError(error); 
        errorStore.setComponentType(2);
      }
    };

    const closeModal = () => {
      rolStore.reset();
      errorStore.clearErrors();
      isVisible.value = false;
    };

    return { 
      rolStore,
      handleSubmit, 
      errorStore, 
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
  max-width: 500px;
  animation: zoomIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.modal-header-venom {
  padding: 1.5rem 1.5rem 0.5rem;
  border-bottom: none;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-title-venom {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--text-main);
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin: 0;
}

.btn-close-venom {
  background: transparent;
  border: 1px solid var(--glass-border);
  color: var(--text-muted);
  width: 2rem;
  height: 2rem;
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
  padding: 1rem 1.5rem 2rem;
}

.form-venom {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1.5rem;
}

@keyframes zoomIn {
  from { transform: scale(0.8); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
</style>
