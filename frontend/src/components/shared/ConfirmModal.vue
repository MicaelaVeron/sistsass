<template>
  <Teleport to="body">
    <div v-if="isVisible" class="modal-backdrop-venom">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-panel modal-venom modal-confirm">
          <div class="modal-header-venom">
            <h5 class="modal-title-venom">
              <i class="bi" :class="iconClass"></i>
              {{ title }}
            </h5>
            <button type="button" class="btn-close-venom" @click="cancel" aria-label="Close">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
          
          <div class="modal-body-venom text-center">
            <div class="confirm-icon-large" :class="variant">
              <i class="bi" :class="iconClass"></i>
            </div>
            <p class="confirm-message">{{ message }}</p>
          </div>

          <div class="form-actions confirm-actions">
            <ActionButton 
              type="button" 
              variant="secondary" 
              @click="cancel"
            >
              {{ cancelText }}
            </ActionButton>
            <ActionButton 
              type="button" 
              :variant="variant" 
              @click="confirm"
            >
              {{ confirmText }}
            </ActionButton>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script>
import { ref, watch } from 'vue';
import ActionButton from './ActionButton.vue';

export default {
  name: 'ConfirmModal',
  components: {
    ActionButton
  },
  props: {
    title: {
      type: String,
      default: 'Confirmar Acción'
    },
    message: {
      type: String,
      required: true
    },
    confirmText: {
      type: String,
      default: 'Confirmar'
    },
    cancelText: {
      type: String,
      default: 'Cancelar'
    },
    variant: {
      type: String,
      default: 'danger' // 'danger', 'warning', 'primary', etc.
    },
    icon: {
      type: String,
      default: ''
    }
  },
  emits: ['confirm', 'cancel'],
  setup(props, { emit }) {
    const isVisible = ref(false);
    const iconClass = ref('');

    watch(() => props.icon, (newVal) => {
       if (newVal) {
          iconClass.value = newVal;
       } else {
          // Defaults based on variant
          switch(props.variant) {
             case 'danger': iconClass.value = 'bi-exclamation-octagon-fill'; break;
             case 'warning': iconClass.value = 'bi-exclamation-triangle-fill'; break;
             case 'success': iconClass.value = 'bi-check-circle-fill'; break;
             default: iconClass.value = 'bi-info-circle-fill';
          }
       }
    }, { immediate: true });

    // Alternativa al JS puro: controlar todo mediante estado de Vue
    const show = () => {
      isVisible.value = true;
    };

    const hide = () => {
      isVisible.value = false;
    };

    const confirm = () => {
      emit('confirm');
      hide();
    };

    const cancel = () => {
      emit('cancel');
      hide();
    };

    return {
      isVisible,
      iconClass,
      show,
      hide,
      confirm,
      cancel
    };
  }
}
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

.modal-confirm {
  width: 90vw;
  max-width: 450px;
  margin: 0 auto;
  animation: zoomIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.modal-venom {
  background: var(--bg-card);
  border: 1px solid var(--glass-border);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
}

/* Resto de estilos iguales */

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
  padding: 1rem 2rem 2rem;
}

.confirm-icon-large {
  font-size: 4rem;
  margin-bottom: 1rem;
  animation: scaleIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.confirm-icon-large.danger { color: var(--danger); text-shadow: 0 0 20px rgba(255, 77, 77, 0.4); }
.confirm-icon-large.warning { color: var(--warning); text-shadow: 0 0 20px rgba(255, 170, 0, 0.4); }
.confirm-icon-large.success { color: var(--primary); text-shadow: 0 0 20px rgba(0, 255, 136, 0.4); }
.confirm-icon-large.primary { color: var(--secondary); text-shadow: 0 0 20px rgba(0, 204, 255, 0.4); }

.confirm-message {
  font-size: 1.1rem;
  color: var(--text-main);
  margin-bottom: 0;
}

.confirm-actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
  padding: 0 2rem 2rem;
}

@keyframes zoomIn {
  from { transform: scale(0.8); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
</style>
