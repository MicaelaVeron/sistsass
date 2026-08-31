<template>
  <div class="custom-select-container" :class="{ 'is-open': isOpen, 'is-disabled': disabled }" ref="container">
    <div class="select-trigger" @click="toggleOpen">
      <!-- Icon Slot -->
      <slot name="icon"></slot>
      
      <span class="selected-text" :class="{ 'placeholder': !itemLabel(modelValue) }">
        {{ itemLabel(modelValue) || placeholder }}
      </span>
      
      <i class="bi bi-chevron-down arrow"></i>
    </div>

    <transition name="fade">
      <ul v-show="isOpen" class="options-list">
        <li 
          v-for="option in options" 
          :key="option[valueKey]" 
          class="option-item" 
          :class="{ 'selected': option[valueKey] === modelValue }"
          @click="selectOption(option)"
        >
          <span class="option-label">{{ option[labelKey] }}</span>
          <i v-if="option[valueKey] === modelValue" class="bi bi-check-lg check-icon"></i>
        </li>
        <li v-if="options.length === 0" class="option-item no-results">
            No hay opciones
        </li>
      </ul>
    </transition>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';

export default {
  name: 'CustomSelect',
  props: {
    modelValue: {
      type: [String, Number, Object],
      default: null
    },
    options: {
      type: Array,
      default: () => []
    },
    placeholder: {
      type: String,
      default: 'Seleccione una opción'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    valueKey: {
      type: String,
      default: 'id'
    },
    labelKey: {
      type: String,
      default: 'name'
    }
  },
  emits: ['update:modelValue', 'change'],
  setup(props, { emit }) {
    const isOpen = ref(false);
    const container = ref(null);

    const toggleOpen = () => {
      if (!props.disabled) {
        isOpen.value = !isOpen.value;
      }
    };

    const selectOption = (option) => {
      emit('update:modelValue', option[props.valueKey]);
      emit('change', option[props.valueKey]);
      isOpen.value = false;
    };

    const itemLabel = (val) => {
        if (val === null || val === undefined) return null;
        const found = props.options.find(o => o[props.valueKey] === val);
        return found ? found[props.labelKey] : null;
    }

    const handleClickOutside = (event) => {
      if (container.value && !container.value.contains(event.target)) {
        isOpen.value = false;
      }
    };

    onMounted(() => {
      document.addEventListener('click', handleClickOutside);
    });

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside);
    });

    return {
      isOpen,
      container,
      toggleOpen,
      selectOption,
      itemLabel
    };
  }
};
</script>

<style scoped>
/* Container & Trigger Styles match previous... */
.custom-select-container {
  position: relative;
  width: 100%;
}

.select-trigger {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  padding: 0.8rem 1rem;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid transparent; /* Hide border by default for cleaner look */
  border-radius: var(--radius-sm);
  color: var(--text-main);
  cursor: pointer;
  transition: all 0.3s;
  user-select: none;
}

.custom-select-container.is-open .select-trigger,
.select-trigger:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.1); /* Subtle border on hover */
}

.custom-select-container.is-open .select-trigger {
    border-color: var(--primary);
    box-shadow: 0 0 15px rgba(0, 255, 136, 0.1);
}

.custom-select-container.is-disabled .select-trigger {
    cursor: not-allowed; /* Explicit cursor */
    opacity: 0.5;
    background: rgba(255, 255, 255, 0.02);
}

/* Remove pointer-events: none from container so we can show cursor */
.custom-select-container.is-disabled {
    cursor: not-allowed;
}

.selected-text {
    flex-grow: 1;
    margin-left: 0.5rem;
    font-weight: 500;
}

.selected-text.placeholder {
    color: var(--text-muted);
    font-weight: 400;
}

.arrow {
  color: var(--text-muted);
  font-size: 0.8rem;
  transition: transform 0.3s;
}

.custom-select-container.is-open .arrow {
  transform: rotate(180deg);
  color: var(--primary);
}

/* Options List */
.options-list {
  position: absolute;
  top: calc(100% + 5px);
  left: 0;
  width: 100%;
  max-height: 250px;
  overflow-y: auto;
  background: #151515; 
  border: 1px solid transparent; /* Completely transparent border */
  border-top: none; 
  border-radius: var(--radius-sm);
  z-index: 100;
  list-style: none;
  padding: 0;
  margin: 0;
  box-shadow: 0 10px 30px rgba(0,0,0,0.8);
}

.option-item {
  padding: 0.8rem 1rem;
  cursor: pointer;
  color: #e0e0e0;
  transition: background 0.2s;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: none;
}

.option-item:last-child {
    border-bottom: none;
}

.option-label {
    flex-grow: 1;
}

.check-icon {
    color: var(--primary);
    font-size: 1rem;
}

.option-item:hover {
  background: rgba(0, 255, 136, 0.1);
  color: var(--primary);
}

.option-item.selected {
  background: rgba(0, 255, 136, 0.05);
  color: var(--primary); 
  font-weight: 600;
  border-left: 3px solid var(--primary); /* Visual cue */
}

.option-item.no-results {
    cursor: default;
    justify-content: center;
    font-style: italic;
    color: var(--text-muted);
}

/* Scrollbar styling */
.options-list::-webkit-scrollbar {
  width: 6px;
}
.options-list::-webkit-scrollbar-track {
  background: #111;
}
.options-list::-webkit-scrollbar-thumb {
  background: #333;
  border-radius: 3px;
}
.options-list::-webkit-scrollbar-thumb:hover {
  background: var(--primary);
}

/* Fade Transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s, transform 0.2s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
