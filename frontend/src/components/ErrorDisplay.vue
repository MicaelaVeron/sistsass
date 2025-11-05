<template>
    <div v-if="errorStore.hasErrors" class="alert alert-danger" role="alert">
      <p v-if="errorStore.message" class="alert-heading">{{ errorStore.message }}</p>
      <ul v-if="Object.keys(errorStore.data).length > 0" >
        <li v-for="(messages, field) in errorStore.data" :key="field">
          <strong>{{ field }}:</strong> {{ messages.join(', ') }}
        </li>
      </ul>
    </div>
    <!-- Alerta de éxito -->
    <div v-if="errorStore.hasSuccess" class="alert alert-success" role="alert">
      {{ errorStore.successMessage }}
    </div>
  </template>
  <script>
  import { useErrorMessages } from '@/stores/modules/errors_messages';
  export default {
      setup() {
        const errorStore = useErrorMessages();
        return { errorStore };
      },
    };
  </script>