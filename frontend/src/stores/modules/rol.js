import { defineStore } from 'pinia';
import { useRouter } from 'vue-router'
import axios from '../../axios';
export const useRol = defineStore('rol', {
    state: () => ({
        roles : [],
        organizations: [],
        operationType: null,
        guards_name : [
            { id: '1', value: 'web' },
            { id: '2', value: 'api' }
        ],
        form:{
            id: null,
            name:'',
            guard_name:'',
        },
    }),
    actions: {
        async fetchRoles () {
            const res = await axios.get('/api/rol-index') // devuelve árbol
            this.roles = res.data
        },
        async edit(rol) {
            try {
                const res = await axios.get(`/api/rol-edit/${rol.id}`);  
                 
                this.form.id = res.data.id;
                this.form.name = res.data.name;
                this.form.guard_name = res.data.guard_name;
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        async send() {
            try {
                if (this.operationType === 'update') {
                    const res = await axios.put(`/api/rol-update/${this.form.id}`, this.form); 
                    this.fetchRoles()    
                    return true;  
                } 
                if (this.operationType === 'create') {    
                    const res = await axios.post('/api/rol-store', this.form);       
                    this.fetchRoles()    
                    return true;
                }
            } catch (error) {
                console.log('Login error:', error);
                throw error;
            }
        },
        async delete(rol) {
            try {
                const res = await axios.get(`/api/rol-destroy/${rol.id}`);   
                this.fetchRoles(); // Actualiza la lista después de eliminar
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        reset() {
            this.form.id = null;
            this.form.name  = '';
            this.form.guard_name = '';
        },
        setOperationType(type) {
            this.operationType = type;
        }
    },
    getters: {
       
    }
});