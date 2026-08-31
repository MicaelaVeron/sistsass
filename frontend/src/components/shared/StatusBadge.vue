<template>
  <span :class="['status-badge', variant]">
    <i v-if="icon" :class="icon"></i>
    <slot></slot>
  </span>
</template>

<script>
export default {
  name: 'StatusBadge',
  props: {
    variant: {
      type: String,
      default: 'default', // default, success, danger, warning, info
      validator: (value) => ['default', 'success', 'danger', 'warning', 'info'].includes(value)
    },
    icon: {
      type: String,
      default: ''
    }
  }
}
</script>

<style scoped>
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  padding: 0.3rem 0.8rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border: 1px solid;
}

.status-badge.default {
  background: rgba(160, 160, 160, 0.1);
  border-color: rgba(160, 160, 160, 0.3);
  color: var(--text-muted);
}

.status-badge.success {
  background: rgba(0, 255, 136, 0.1);
  border-color: rgba(0, 255, 136, 0.3);
  color: var(--primary);
}

.status-badge.danger {
  background: rgba(255, 77, 77, 0.1);
  border-color: rgba(255, 77, 77, 0.3);
  color: #ff4d4d;
}

.status-badge.warning {
  background: rgba(255, 170, 0, 0.1);
  border-color: rgba(255, 170, 0, 0.3);
  color: #ffaa00;
}

.status-badge.info {
  background: rgba(112, 0, 255, 0.1);
  border-color: rgba(112, 0, 255, 0.3);
  color: var(--secondary);
}

.status-badge i {
  font-size: 0.9rem;
}
</style>
