import { defineStore } from 'pinia';
import { useRouter } from 'vue-router'
import axios from '../../axios';
export const useMenu = defineStore('menu', {
    state: () => ({
        menus : [],
        form:{
            id: null,
            name:'',
            url: '',
            parent_id: null
        },
        parentId : null
    }),
    actions: {
        async fetchMenus () {
            const res = await axios.get('/api/menu-index') // devuelve árbol
            this.menus = res.data
        },
        async edit(menu) {
            try {
                const res = await axios.get(`/api/menu-edit/${menu.id}`);   
                this.form.id = res.data.id;
                this.form.name = res.data.name;
                this.form.url = res.data.url;
                this.form.parent_id = res.data.parent_id;
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        async send() {
            try {
                const response = await axios.post('/api/menu-store', this.form);       
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        async delete(menu) {
            try {
                const res = await axios.get(`/api/menu-destroy/${menu.id}`);   
                this.fetchMenus(); // Actualiza la lista de menús después de eliminar
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        reset() {
            this.form.id = null;
            this.form.name  = '';
            this.form.url = '';
            this.parent_id = null;
        }
    },
    getters: {
       
    }
});