import { defineStore } from 'pinia';
import { useRouter } from 'vue-router'
import axios from '../../axios';
export const useBranch = defineStore('branch', {
    state: () => ({
        branches : [],
        organizations : [],
        form:{
            id: null,
            number: '',
            name:'',
            address: '',
            telephone: null,
            organization_id: null,
        },
    }),
    actions: {
        async fetch () {
            const res = await axios.get('/api/branch-index') // devuelve árbol
            this.branches = res.data
        },
        async edit(branch) {
            try {
                const res = await axios.get(`/api/branch-edit/${branch.id}`);  
                 
                this.form.id = res.data.id;
                this.form.name = res.data.name;
                this.form.number = res.data.number;
                this.form.telephone = res.data.telephone;
                this.form.address = res.data.address;
                this.form.organization_id = res.data.organization_id;
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        async send() {
            try {
                const formData = new FormData();
                formData.append("id", this.form.id);
                formData.append("name", this.form.name);
                formData.append("number", this.form.number);
                formData.append("telephone", this.form.telephone);
                formData.append("address", this.form.address);
                formData.append("organization_id", this.form.organization_id);
                
                const response = await axios.post('/api/branch-store', formData, {
                    headers: {
                      'Content-Type': 'multipart/form-data',
                    },
                  });       
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        async delete(branch) {
            try {
                const res = await axios.get(`/api/branch-destroy/${form.id}`);   
                this.fetch(); // Actualiza la lista después de eliminar
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        reset() {
            this.form.id = null;
            this.form.name = '';
            this.form.number = '';
            this.form.telephone = null;
            this.form.address = '';
            this.form.organization_id = '';
            
        }
    },
    getters: {
       
    }
});