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
        branch_id:null,
        organization_branch_id:null,
        formOrganizationRol:{
            id:null,
            user_id:null,
            selectedOrganizationRol: [],
        },
        formOrganizationBranch:{
            id:null,
            user_id:null,
            userOrganizations: [],
            selectedOrganizationBranch: [],
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
        async delete(user) {
            try {
                const res = await axios.get(`/api/user-destroy/${user.id}`);   
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
            this.formOrganizationBranch.user_id = id;
        },
        async getBranches (organization_id) {
            const res = await axios.get(`/api/branch-getBranchesWithOrganization/${organization_id}`);
            this.branches = res.data
        },
        existeOrganizationBranch: function(id){
			if (this.formOrganizationBranch.selectedOrganizationBranch.length == 0) {
				return true;
			}
			let organizationBranch = this.formOrganizationBranch.selectedOrganizationBranch.filter(e => e.id == id);
			if (organizationBranch.length == 0) {
				return true;
			}
			return false;
		},
        setOrganizationBranch() {
            let dato = {};
			dato = Object.assign({});
			dato.id = Math.random();
			dato.organization_id = this.organization_branch_id;
            dato.branch_id = this.branch_id;
            let branch = this.branches.find(e=>e.id == this.branch_id);
            let organization = this.organizations.find(e=>e.id == this.organization_branch_id);
            dato.organization = organization;
            dato.branch = branch;
            if (this.existeOrganizationBranch(dato.id)) {
				this.formOrganizationBranch.selectedOrganizationBranch.push(dato);

                this.resetAddOrganizationBranch();
			}
        },
        resetAddOrganizationBranch() {
            this.organization_branch_id = null;
            this.branch_id = null;
        },
        deleteOrganizationRol: function(id,organization_id){
			let dato = this.formOrganizationRol.selectedOrganizationRol.filter(e => e.id != id);
            if (dato) {
				this.formOrganizationRol.selectedOrganizationRol = dato;
            }
		},
        deleteOrganizationBranch: function(id){
			let dato = this.formOrganizationBranch.selectedOrganizationBranch.filter(e => e.id != id);

			if (dato) {
				this.formOrganizationBranch.selectedOrganizationBranch = dato;
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
        async getOrganizationAndRolesWithUser (user_id) {
            const res = await axios.get(`/api/user-getOrganizationAndAssignedRoles/${user_id}`);
            this.formOrganizationRol.selectedOrganizationRol = res.data
        },
    },
    getters: {
       
    }
});