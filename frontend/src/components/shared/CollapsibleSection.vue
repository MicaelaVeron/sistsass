<template>
  <div class="collapsible-section glass-panel" :class="{ 'is-open': isOpen }">
    <div class="section-header" @click="toggle">
      <div class="header-content">
        <i v-if="icon" :class="icon" class="section-icon"></i>
        <h4 class="section-title">{{ title }}</h4>
        <span v-if="badge !== null" class="section-badge">{{ badge }}</span>
      </div>
      <i class="bi bi-chevron-down toggle-icon"></i>
    </div>
    <div v-show="isOpen" class="section-body">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CollapsibleSection',
  props: {
    title: {
      type: String,
      required: true
    },
    icon: {
      type: String,
      default: ''
    },
    badge: {
      type: [Number, String],
      default: null
    },
    defaultOpen: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      isOpen: this.defaultOpen
    }
  },
  methods: {
    toggle() {
      this.isOpen = !this.isOpen;
    }
  }
}
</script>

<style scoped>
.collapsible-section {
  margin-bottom: 0.5rem;
  overflow: hidden;
  transition: var(--transition-fast);
}

.section-header {
  padding: 1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  cursor: pointer;
  background: rgba(0, 0, 0, 0.2);
  transition: var(--transition-fast);
}

.section-header:hover {
  background: rgba(0, 0, 0, 0.4);
}

.header-content {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.section-icon {
  color: var(--primary);
  font-size: 1.2rem;
}

.section-title {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 700;
  color: #ffffff;
}

.section-badge {
  background: var(--primary);
  color: #000;
  padding: 0.1rem 0.5rem;
  border-radius: 10px;
  font-size: 0.75rem;
  font-weight: 700;
}

.toggle-icon {
  transition: var(--transition-smooth);
}

.is-open .toggle-icon {
  transform: rotate(180deg);
}

.section-body {
  padding: 1rem;
  border-top: 1px solid var(--glass-border);
}
</style>
