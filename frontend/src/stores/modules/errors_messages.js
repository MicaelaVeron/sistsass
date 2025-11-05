import { defineStore } from 'pinia';
import { useRouter } from 'vue-router'
import axios from '../../axios';
export const useErrorMessages = defineStore('error_messages', {
    state: () => ({
        message: null,
        data: {},
        status: null,
        successMessage: null,
        componentType:3
    }),
    actions: {
        async setError(error) {
            if (error.response?.status === 422) {
                this.data = error.response.data.errors || {};
                this.status = error.response.status;
            } else {
                this.message = error.response?.data.message || 'Error al iniciar sesión';
                this.status = error.response.status;
            }
            // Ocultar mensaje después de 4 segundos
            setTimeout(() => {
                this.clearErrors();
            }, 4000);
        },
        clearErrors() {
            this.message = null;
            this.data = {};
            this.status = null;
        },
        setSuccess(model) {
            this.clearErrors();
            this.successMessage = 'Procesado Exitosamente';
            // Ocultar mensaje después de 4 segundos
            setTimeout(() => {
                this.successMessage = null;
            }, 4000);
        },
        clearSuccess() {
            this.successMessage = null;
        },
        setComponentType(type) {
            this.componentType = type;
        },
    },
    getters: {
        hasErrors: (state) => !!state.message || Object.keys(state.data).length > 0,
        hasSuccess: (state) => !!state.successMessage,
    },
});