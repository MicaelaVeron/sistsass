<template>
  <div class="error-display-wrapper">
    <!-- Alerta de Error -->
    <div v-if="errorStore.hasErrors" class="venom-alert alert-error" role="alert">
      <div class="alert-icon">
        <i class="bi bi-exclamation-triangle-fill"></i>
      </div>
      <div class="alert-content">
        <h5 v-if="errorStore.message" class="alert-title">{{ errorStore.message }}</h5>
        <ul v-if="Object.keys(errorStore.data).length > 0" class="alert-list">
          <li v-for="(messages, field) in errorStore.data" :key="field">
            <strong>{{ field }}:</strong> {{ messages.join(', ') }}
          </li>
        </ul>
      </div>
    </div>

    <!-- Alerta de Éxito -->
    <div v-if="errorStore.hasSuccess" class="venom-alert alert-success" role="alert">
      <div class="alert-icon">
        <i class="bi bi-check-circle-fill"></i>
      </div>
      <div class="alert-content">
        <h5 class="alert-title mb-0">{{ errorStore.successMessage }}</h5>
      </div>
    </div>
  </div>
</template>

<script>
import { useErrorMessages } from '@/stores/modules/errors_messages';
export default {
  setup() {
    const errorStore = useErrorMessages();
    return { errorStore };
  },
};
</script>

<style scoped>
.error-display-wrapper {
  margin-bottom: 1.5rem;
}

.venom-alert {
  display: flex;
  gap: 1rem;
  padding: 1rem 1.5rem;
  border-radius: var(--radius-md);
  margin-bottom: 1rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  align-items: flex-start;
  animation: slideIn 0.3s ease-out forwards;
}

.alert-icon {
  font-size: 1.5rem;
  line-height: 1;
}

.alert-content {
  flex-grow: 1;
}

.alert-title {
  margin: 0 0 0.5rem 0;
  font-weight: 700;
  font-size: 1rem;
}

.alert-list {
  margin: 0;
  padding-left: 1.2rem;
  font-size: 0.9rem;
  opacity: 0.9;
}

.alert-error {
  background: rgba(255, 77, 77, 0.1);
  border: 1px solid rgba(255, 77, 77, 0.3);
  color: var(--danger);
  border-left: 4px solid var(--danger);
}
.alert-error .alert-title, .alert-error .alert-list {
  color: #fff;
}

.alert-success {
  background: rgba(0, 255, 136, 0.1);
  border: 1px solid rgba(0, 255, 136, 0.3);
  color: var(--primary);
  border-left: 4px solid var(--primary);
}
.alert-success .alert-title {
  color: #fff;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>