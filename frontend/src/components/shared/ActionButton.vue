<template>
  <button 
    :class="['action-btn', variant, size]" 
    @click="$emit('click')"
    :disabled="disabled"
  >
    <i v-if="icon" :class="icon"></i>
    <slot></slot>
  </button>
</template>

<script>
export default {
  name: 'ActionButton',
  props: {
    variant: {
      type: String,
      default: 'primary', // primary, secondary, danger, success, warning
      validator: (value) => ['primary', 'secondary', 'danger', 'success', 'warning'].includes(value)
    },
    size: {
      type: String,
      default: 'md', // sm, md, lg
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    icon: {
      type: String,
      default: ''
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: ['click']
}
</script>

<style scoped>
.action-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  border: 1px solid;
  border-radius: var(--radius-sm);
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition-fast);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-size: 0.85rem;
  background: transparent;
}

.action-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Sizes */
.action-btn.sm {
  padding: 0.4rem 0.8rem;
  font-size: 0.75rem;
}

.action-btn.md {
  padding: 0.6rem 1.2rem;
  font-size: 0.85rem;
}

.action-btn.lg {
  padding: 0.75rem 1.5rem;
  font-size: 0.9rem;
}

/* Variants */
.action-btn.primary {
  border-color: var(--primary);
  color: var(--primary);
}

.action-btn.primary:hover:not(:disabled) {
  background: var(--primary);
  color: #000;
  box-shadow: 0 0 20px var(--primary-glow);
}

.action-btn.secondary {
  border-color: var(--secondary);
  color: var(--secondary);
}

.action-btn.secondary:hover:not(:disabled) {
  background: var(--secondary);
  color: #fff;
  box-shadow: 0 0 20px var(--secondary-glow);
}

.action-btn.danger {
  border-color: #ff4d4d;
  color: #ff4d4d;
}

.action-btn.danger:hover:not(:disabled) {
  background: #ff4d4d;
  color: #fff;
  box-shadow: 0 0 20px rgba(255, 77, 77, 0.4);
}

.action-btn.success {
  border-color: #00ff88;
  color: #00ff88;
}

.action-btn.success:hover:not(:disabled) {
  background: #00ff88;
  color: #000;
  box-shadow: 0 0 20px rgba(0, 255, 136, 0.4);
}

.action-btn.warning {
  border-color: #ffaa00;
  color: #ffaa00;
}

.action-btn.warning:hover:not(:disabled) {
  background: #ffaa00;
  color: #000;
  box-shadow: 0 0 20px rgba(255, 170, 0, 0.4);
}

.action-btn i {
  font-size: 1rem;
}
</style>
