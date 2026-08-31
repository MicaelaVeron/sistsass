<template>
  <li class="nav-item">
    <!-- Submenu Parent -->
    <div v-if="item.children?.length" class="submenu-wrapper">
      <div 
        class="nav-link" 
        @click="isOpen = !isOpen"
        :class="{ 'active': isOpen }"
      >
        <i :class="item.icon || 'bi bi-folder'"></i>
        <span class="link-text">{{ item.name }}</span>
        <i class="bi bi-chevron-down ms-auto arrow" :class="{ 'rotated': isOpen }"></i>
      </div>
      
      <transition name="slide">
        <ul v-show="isOpen" class="submenu-list">
          <RecursiveMenu
            v-for="child in item.children"
            :key="child.id"
            :item="child"
            @navigate="$emit('navigate', $event)"
          />
        </ul>
      </transition>
    </div>

    <!-- Leaf Item -->
    <a 
      v-else 
      href="#" 
      class="nav-link" 
      @click.prevent="$emit('navigate', item.url)"
    >
      <i :class="item.icon || 'bi bi-circle'"></i>
      <span class="link-text">{{ item.name }}</span>
    </a>
  </li>
</template>

<script>
import { ref } from 'vue';

export default {
  name: "RecursiveMenu",
  props: {
    item: { type: Object, required: true }
  },
  setup() {
    const isOpen = ref(false);
    return { isOpen };
  }
};
</script>

<style scoped>
.submenu-wrapper {
  display: flex;
  flex-direction: column;
}

.submenu-list {
  list-style: none;
  padding: 0;
  margin: 0;
  padding-left: 1rem; /* Indentation for hierarchy */
  overflow: hidden;
}

.arrow {
  font-size: 0.8rem;
  transition: transform 0.3s;
}

.arrow.rotated {
  transform: rotate(180deg);
}

.nav-link {
  cursor: pointer;
  user-select: none;
}

/* Slide Animation */
.slide-enter-active,
.slide-leave-active {
  transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
  max-height: 500px;
  opacity: 1;
}

.slide-enter-from,
.slide-leave-to {
  max-height: 0;
  opacity: 0;
}
</style>
