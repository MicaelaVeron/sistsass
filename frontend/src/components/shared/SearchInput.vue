<template>
  <div class="search-input-container">
    <i class="bi bi-search search-icon"></i>
    <input
      type="text"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :placeholder="placeholder"
      class="input-venom search-input"
    />
    <button v-if="modelValue" class="clear-btn" @click="$emit('update:modelValue', '')">
      <i class="bi bi-x"></i>
    </button>
  </div>
</template>

<script>
export default {
  name: 'SearchInput',
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: 'Buscar...'
    }
  },
  emits: ['update:modelValue']
}
</script>

<style scoped>
.search-input-container {
  position: relative;
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

.search-icon {
  position: absolute;
  left: 1rem;
  color: var(--text-muted);
}

.search-input {
  width: 100%;
  padding: 0.8rem 2.5rem 0.8rem 2.5rem;
  background: rgba(0, 0, 0, 0.3);
  border: 1px solid var(--glass-border);
  border-radius: var(--radius-md);
  color: var(--text-main);
  transition: var(--transition-fast);
  outline: none;
}

.search-input:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 2px var(--primary-glow);
  background: rgba(0, 0, 0, 0.5);
}

.clear-btn {
  position: absolute;
  right: 1rem;
  background: transparent;
  border: none;
  color: var(--text-muted);
  cursor: pointer;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.clear-btn:hover {
  color: var(--text-main);
}
</style>
