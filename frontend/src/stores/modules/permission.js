import { defineStore } from 'pinia';
import { useRouter } from 'vue-router'
import axios from '../../axios';
export const usePermission = defineStore('permission', {
    state: () => ({
        permissions : [],
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
        async fetchPermissions () {
            const res = await axios.get('/api/permission-index') // devuelve árbol
            this.permissions = res.data
        },
        async edit(permission) {
            try {
                const res = await axios.get(`/api/permission-edit/${permission.id}`);  
                 
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
                    const res = await axios.put(`/api/permission-update/${this.form.id}`, this.form); 
                    this.fetchPermissions()    
                    return true;  
                } 
                if (this.operationType === 'create') {    
                    const res = await axios.post('/api/permission-store', this.form);       
                    this.fetchPermissions()    
                    return true;
                }
            } catch (error) {
                console.log('Login error:', error);
                throw error;
            }
        },
        async delete(permission) {
            try {
                const res = await axios.get(`/api/permission-destroy/${permission.id}`);   
                this.fetchPermissions(); // Actualiza la lista después de eliminar
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