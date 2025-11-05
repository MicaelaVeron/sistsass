<template>
    <div>
      <ul>
        <li class="list-group-item " v-for="menu in menus" :key="menu.id">
          <label>
            <input
              type="checkbox"
              :value="menu.id"
              :checked="selectedMenus.includes(menu.id)"
               @change="onSelect(menu)"
              />
            {{ menu.name }}
          </label>
          <!-- hijos -->
          <MenuTree
          v-if="menu.children && menu.children.length > 0"
          :menus="menu.children"
          :selectedMenus="selectedMenus"
          :parent="menu"
        />
        </li>
      </ul>
    </div>
  </template>
  
  <script>

  import { useOrganization } from '@/stores/modules/organization';
  export default {
    props: {
      menus: {
        type: Array,
        required: true,
      },
      selectedMenus: {
        type: Array,
        required: true,
      },
      parent: { type: Object, default: null } // ← nuevo prop
    },
   
    setup(props) {
      const organizationStore = useOrganization(); 
      const onSelect = (menu) => {
        const selectedMenus = props.selectedMenus

        if (!selectedMenus.includes(menu.id)) {
          // ✅ marcar menú
          selectedMenus.push(menu.id)

          // ✅ si tiene padre, marcarlo también
          markParent(props.parent, selectedMenus)
        } else {
          // ❌ desmarcar menú
          const index = selectedMenus.indexOf(menu.id)
          selectedMenus.splice(index, 1)

          // ❌ si es un padre, desmarcar sus hijos
          if (menu.children) {
            removeChildren(menu.children, selectedMenus)
          }
        }
      };
       // Marca recursivamente al padre
      const markParent = (parent, selectedMenus) => {
        if (parent && !selectedMenus.includes(parent.id)) {
          selectedMenus.push(parent.id)
        }
      };
      // Desmarca recursivamente los hijos
      const removeChildren = (children, selectedMenus) => {
        children.forEach((child) => {
          const index = selectedMenus.indexOf(child.id)
          if (index !== -1) selectedMenus.splice(index, 1)
          if (child.children) removeChildren(child.children, selectedMenus)
        })
      };
      return { organizationStore,onSelect };
    },
  };
  </script>
  