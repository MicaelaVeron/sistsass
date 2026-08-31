<template>
  <div class="data-card glass-panel">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'DataCard'
}
</script>

<style scoped>
.data-card {
  padding: 1.5rem;
  margin-bottom: 1rem;
  transition: var(--transition-fast);
  position: relative;
  overflow: hidden;
}

.data-card:hover {
  background: var(--bg-card-hover);
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg);
}

.data-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 3px;
  height: 100%;
  background: linear-gradient(180deg, var(--primary), var(--secondary));
  opacity: 0;
  transition: var(--transition-fast);
}

.data-card:hover::before {
  opacity: 1;
}
</style>
