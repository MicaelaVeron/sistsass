<template>
  <div class="checkbox-venom-wrapper">
    <label class="checkbox-venom-container">
      <input
        type="checkbox"
        :checked="modelValue"
        @change="$emit('update:modelValue', $event.target.checked)"
        :disabled="disabled"
      />
      <span class="checkmark"></span>
      <span class="label-text">
        <slot></slot>
      </span>
    </label>
  </div>
</template>

<script>
export default {
  name: 'CheckboxVenom',
  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue']
}
</script>

<style scoped>
.checkbox-venom-wrapper {
  margin-bottom: 0.5rem;
}

.checkbox-venom-container {
  display: flex;
  align-items: center;
  position: relative;
  cursor: pointer;
  padding-left: 35px;
  min-height: 25px;
  user-select: none;
  font-size: 0.95rem;
  color: #ffffff;
  font-weight: 500;
  text-shadow: 0 2px 4px rgba(0,0,0,0.8);
  transition: var(--transition-fast);
}

.checkbox-venom-container:hover {
  color: #ffffff;
  text-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
}

/* Hide the browser's default checkbox */
.checkbox-venom-container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 22px;
  width: 22px;
  background-color: rgba(0, 0, 0, 0.4);
  border: 1px solid var(--glass-border);
  border-radius: 4px;
  transition: var(--transition-fast);
}

/* On mouse-over, add a border color */
.checkbox-venom-container:hover input ~ .checkmark {
  border-color: var(--primary);
  box-shadow: 0 0 10px rgba(0, 255, 136, 0.2);
}

/* When the checkbox is checked, add a neon background */
.checkbox-venom-container input:checked ~ .checkmark {
  background-color: rgba(0, 255, 136, 0.2);
  border-color: var(--primary);
  box-shadow: 0 0 10px rgba(0, 255, 136, 0.4);
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.checkbox-venom-container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.checkbox-venom-container .checkmark:after {
  left: 7px;
  top: 3px;
  width: 6px;
  height: 12px;
  border: solid var(--primary);
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

/* Disabled state */
.checkbox-venom-container input:disabled ~ .checkmark {
  background-color: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.2);
  cursor: not-allowed;
}

.checkbox-venom-container input:disabled ~ .label-text {
  color: var(--text-muted);
  cursor: not-allowed;
}
</style>
