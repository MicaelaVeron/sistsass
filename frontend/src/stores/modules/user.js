import { defineStore } from 'pinia';
import { useRouter } from 'vue-router'
import axios from '../../axios';
export const useUser = defineStore('user', {
    state: () => ({
        users : [],
        operationType: null,
        branches : [],
        organizations: [],
        roles:[],
        form:{
            id: null,
            name:'',
            email:'',
            password:'',
        },
        organization_id:null,
        rol_id:null,
        formOrganizationRol:{
            id:null,
            user_id:null,
            selectedOrganizationRol: [],
        },
        formBranch:{
            id:null,
            user_id:null,
            selectedBranch: [],
        },
    }),
    actions: {
        async fetchUsers () {
            const res = await axios.get('/api/user-index') // devuelve árbol
            this.users = res.data
        },
        async edit(user) {
            try {
                const res = await axios.get(`/api/user-edit/${user.id}`);  
                 
                this.form.id = res.data.id;
                this.form.name = res.data.name;
                this.form.email = res.data.email;
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        async send() {
            try {
                if (this.operationType === 'update') {
                    const res = await axios.put(`/api/user-update/${this.form.id}`, this.form); 
                    this.fetchUsers()    
                    return true;  
                } 
                if (this.operationType === 'create') {    
                    const res = await axios.post('/api/user-store', this.form);       
                    this.fetchUsers()    
                    return true;
                }
            } catch (error) {
                console.log('Login error:', error);
                throw error;
            }
        },
        async inactive(user) {
            try {
                const res = await axios.get(`/api/user-inactive/${user.id}`);   
                this.fetchUsers(); // Actualiza la lista después de eliminar
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        async active(user) {
            try {
                const res = await axios.get(`/api/user-active/${user.id}`);   
                this.fetchUsers(); // Actualiza la lista después de eliminar
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        reset() {
            this.form.id = null;
            this.form.name  = '';
            this.form.email = '';
            this.form.password = '';
        },
        setOperationType(type) {
            this.operationType = type;
        },
        async getOrganizations () {
            const res = await axios.get('/api/organization-index') // devuelve árbol
            this.organizations = res.data
        },
        async getRoles (organization_id) {
            const res = await axios.get(`/api/organization-rol-getRolWithOrganization/${organization_id}`);
            this.roles = res.data
        },
        async getUserOrganizations (user_id) {
            const res = await axios.get(`/api/user-getOrganizationAssigned/${user_id}`);
            this.formOrganizationBranch.userOrganizations = res.data
        },
        resetAddOrganizationRol() {
            this.organization_id = null;
            this.rol_id = null;
        },
        existeOrganizationRol: function(id){
			if (this.formOrganizationRol.selectedOrganizationRol.length == 0) {
				return true;
			}
			let organizationRol = this.formOrganizationRol.selectedOrganizationRol.filter(e => e.id == id);
			if (organizationRol.length == 0) {
				return true;
			}
			return false;
		},
        setOrganizationRol() {
            let dato = {};
			dato = Object.assign({});
			dato.id = Math.random();
			dato.organization_id = this.organization_id;
            dato.rol_id = this.rol_id;
            let rol = this.roles.find(e=>e.id == this.rol_id);
            let organization = this.organizations.find(e=>e.id == this.organization_id);
            dato.organization = organization;
            dato.rol = rol;
            if (this.existeOrganizationRol(dato.id)) {
				this.formOrganizationRol.selectedOrganizationRol.push(dato);
                this.resetAddOrganizationRol();
			}
        },
        setUser(id) {
            this.formOrganizationRol.user_id = id;
            this.formBranch.user_id = id;
        },
        async getBranches (user_id) {
            const res = await axios.get(`/api/user-getBranchesWithUserOrganization/${user_id}`);
            this.branches = res.data
        },
        setBranches(branchId) {
            const index = this.formBranch.selectedBranch.indexOf(branchId);
            if (index === -1) {
              this.formBranch.selectedBranch.push(branchId); // Agregar permiso si no está en el array
            } else {
              this.formBranch.selectedBranch.splice(index, 1); // Eliminar permiso si ya está en el array
            }
        },
        resetBranchList(){
            this.formBranch.selectedBranch = [];
        },
        resetOrganizationRolList(){
            this.formOrganizationRol.selectedOrganizationRol = [];
        },
        deleteOrganizationRol: function(id,organization_id){
			let dato = this.formOrganizationRol.selectedOrganizationRol.filter(e => e.id != id);
            if (dato) {
				this.formOrganizationRol.selectedOrganizationRol = dato;
            }
		},
        async sendOrganizationRol() {
            try {
                const response = await axios.post('/api/user-assignOrganizationsAndRoles', this.formOrganizationRol);       
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        async sendBranch() {
            try {
                const response = await axios.post('/api/user-assignBranches', this.formBranch);       
                return true;
            } catch (error) {
                console.error('Login error:', error);
                throw error;
            }
        },
        async getOrganizationAndRolesWithUser (user_id) {
            const res = await axios.get(`/api/user-getOrganizationAndAssignedRoles/${user_id}`);
            this.formOrganizationRol.selectedOrganizationRol = res.data
        },
        async getBranchWithUser (user_id) {
            const res = await axios.get(`/api/user-getBranchAssigned/${user_id}`);
            this.formBranch.selectedBranch = res.data
        },
    },
    getters: {
       
    }
});