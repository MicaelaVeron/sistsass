<template>
  <div class="form-group">
    <label v-if="label" :for="id" class="form-label">
      {{ label }}
      <span v-if="required" class="required-mark">*</span>
    </label>
    <input
      v-if="type !== 'select'"
      :id="id"
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      class="input-venom"
    />
    <select
      v-else
      :id="id"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      :required="required"
      :disabled="disabled"
      class="input-venom"
    >
      <option v-if="placeholder" :value="null">{{ placeholder }}</option>
      <slot></slot>
    </select>
    <span v-if="hint" class="form-hint">{{ hint }}</span>
  </div>
</template>

<script>
export default {
  name: 'FormInput',
  props: {
    id: {
      type: String,
      required: true
    },
    label: {
      type: String,
      default: ''
    },
    type: {
      type: String,
      default: 'text' // text, email, password, select, etc.
    },
    modelValue: {
      type: [String, Number],
      default: ''
    },
    placeholder: {
      type: String,
      default: ''
    },
    required: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    hint: {
      type: String,
      default: ''
    }
  },
  emits: ['update:modelValue']
}
</script>

<style scoped>
.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--text-main);
  font-weight: 600;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.required-mark {
  color: var(--primary);
  margin-left: 0.2rem;
}

.input-venom {
  width: 100%;
  padding: 0.875rem 1rem;
  background: rgba(0, 0, 0, 0.3);
  border: 1px solid var(--glass-border);
  border-radius: var(--radius-sm);
  color: var(--text-main);
  transition: var(--transition-fast);
  outline: none;
  font-size: 0.95rem;
}

.input-venom:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px var(--primary-glow);
  background: rgba(0, 0, 0, 0.5);
}

.input-venom:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.input-venom::placeholder {
  color: var(--text-muted);
}

.form-hint {
  display: block;
  margin-top: 0.5rem;
  color: var(--text-muted);
  font-size: 0.8rem;
}

/* Select specific styles */
select.input-venom {
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2300ff88' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  padding-right: 2.5rem;
}

select.input-venom option {
  background: #1a1a1a;
  color: var(--text-main);
}
</style>
