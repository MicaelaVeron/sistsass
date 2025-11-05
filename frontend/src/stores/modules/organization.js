import { defineStore } from 'pinia';
import { useRouter } from 'vue-router'
import axios from '../../axios';
export const useOrganization = defineStore('organization', {
    state: () => ({
        organizations : [],
        menus : [],
        roles:[],
        permissions:[],
        form:{
            id: null,
            name:'',
            ruc: '',
            telephone: null,
            address: '',
            email: '',
            logo: '',
        },
        formConfig:{
            id:null,
            rol_id: null,
            organization_id:null,
            selectedMenus: [],
            selectedPermissions:[]
        },
    }),
    actions: {
        async fetchOrganizations () {
            const res = await axios.get('/api/organization-index') // devuelve árbol
            this.organizations = res.data
        },
        async edit(organization) {
            try {
                const res = await axios.get(`/api/organization-edit/${organization.id}`);  
                 
                this.form.id = res.data.id;
                this.form.name = res.data.name;
                this.form.ruc = res.data.ruc;
                this.form.telephone = res.data.telephone;
                this.form.address = res.data.address;
                this.form.email = res.data.email;
                this.form.logo = res.data.logo;
               // console.log(res.data);
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
                formData.append("ruc", this.form.ruc);
                formData.append("telephone", this.form.telephone);
                formData.append("address", this.form.address);
                formData.append("email", this.form.email);
                formData.append("logo", this.form.logo);
                
                const response = await axios.post('/api/organization-store', formData, {
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
        async delete(organization) {
            try {
                const res = await axios.get(`/api/organization-destroy/${organization.id}`);   
                this.fetchOrganizations(); // Actualiza la lista después de eliminar
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        async fetchDetalleRol () {
            if (this.formConfig.rol_id == null) {
                this.resetConfig();
            }else{
                this.formConfig.selectedMenus = [];
                this.formConfig.selectedPermissions = [];
                const res = await axios.get('/api/menu-index') // devuelve árbol
                this.menus = res.data.filter(m => m.parent_id === null);

                const organization_menu_res = await axios.get(`/api/organization-getMenuOrganizationRol/${this.formConfig.rol_id}/${this.formConfig.organization_id}`);   
                this.formConfig.selectedMenus = organization_menu_res.data.map(m => m.id);
                this.getPermissions();

                const organization_permission_res = await axios.get(`/api/organization-getOrganizationRolPermission/${this.formConfig.rol_id}/${this.formConfig.organization_id}`);   
                this.formConfig.selectedPermissions = organization_permission_res.data.map(m => m.id);
                
            }
            
           
           
        },
        reset() {
            this.form.id = null;
            this.form.name = '';
            this.form.ruc = '';
            this.form.telephone = null;
            this.form.address = '';
            this.form.email = '';
            this.form.logo = '';
        },
        resetConfig() {
            this.formConfig.id = null;
            this.formConfig.rol_id = null;
            this.formConfig.selectedMenus=[];
            this.menus=[];
            this.permissions=[];
            this.formConfig.selectedPermissions=[];
        },
        async setOrganization(organization) {     
            this.formConfig.organization_id = organization.id;
        },
        async getRoles () {
            const res = await axios.get('/api/rol-index') // devuelve árbol
            this.roles = res.data
        },
        async getPermissions () {
            const res = await axios.get('/api/permission-index') // devuelve árbol
            this.permissions = res.data
        },
        async sendConfig() {
            try {
                const response = await axios.post('/api/organization-sendConfig', this.formConfig);       
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        setPermissions(permissionId) {
            const index = this.permissions.indexOf(permissionId);
            if (index === -1) {
              this.formConfig.selectedPermissions.push(permissionId); // Agregar permiso si no está en el array
            } else {
              this.formConfig.selectedPermissions.splice(index, 1); // Eliminar permiso si ya está en el array
            }
          },
    },
    getters: {
       
    }
});