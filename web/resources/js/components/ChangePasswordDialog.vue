<template>
  <slot name="activator" :onClick="onClick"></slot>

  <v-snackbar v-model="changePasswordSuccess" :timeout="2000" color="green">
    Changed password successfully
  </v-snackbar>

  <v-dialog
    max-width="500"
    v-model="changePasswordDialog"
    :persistent="isPending"
  >
    <form @submit.prevent="handleChangePassword">
      <v-card
        title="Change password"
        :loading="isPending"
        :disabled="isPending"
      >
        <v-card-text>
          <div
            class="text-h6 text-center text-red-darken-2 font-weight-bold mb-2"
            v-for="(value, index) in error?.response?.data.errors"
            :key="index"
          >
            {{ value[0] }}
          </div>

          <div class="text-subtitle-1 text-medium-emphasis">
            Current password
          </div>
          <v-text-field
            class="mb-2"
            v-model="currentPassword.value.value"
            :append-inner-icon="
              currentPasswordVisible ? 'mdi-eye-off' : 'mdi-eye'
            "
            :type="currentPasswordVisible ? 'text' : 'password'"
            density="compact"
            placeholder="Enter your password"
            prepend-inner-icon="mdi-lock-outline"
            variant="outlined"
            @click:append-inner="
              currentPasswordVisible = !currentPasswordVisible
            "
            :error-messages="currentPassword.errorMessage.value"
          ></v-text-field>

          <div class="text-subtitle-1 text-medium-emphasis">New password</div>
          <v-text-field
            class="mb-2"
            v-model="newPassword.value.value"
            :append-inner-icon="newPasswordVisible ? 'mdi-eye-off' : 'mdi-eye'"
            :type="newPasswordVisible ? 'text' : 'password'"
            density="compact"
            placeholder="Enter your password"
            prepend-inner-icon="mdi-lock-outline"
            variant="outlined"
            @click:append-inner="newPasswordVisible = !newPasswordVisible"
            :error-messages="newPassword.errorMessage.value"
          ></v-text-field>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            text="Close"
            variant="plain"
            @click="changePasswordDialog = false"
          ></v-btn>

          <v-btn
            type="submit"
            text="Save"
            variant="elevated"
            color="green-darken-3"
          ></v-btn>
        </v-card-actions>
      </v-card>
    </form>
  </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useField, useForm } from 'vee-validate';
import { changePasswordSchema } from '@/consts/validationSchemas.ts';
import { ChangePasswordForm } from '@/types/forms/Auth';
import { useChangePasswordMutation } from '@/api/user/hooks.ts';

const { handleSubmit, resetForm } = useForm<ChangePasswordForm>({
  validationSchema: changePasswordSchema,
});

const { mutate, error, isPending } = useChangePasswordMutation(() => {
  resetForm();
  changePasswordSuccess.value = true;
  changePasswordDialog.value = false;
  currentPasswordVisible.value = false;
  newPasswordVisible.value = false;
});

const changePasswordDialog = defineModel('changePasswordDialog', {
  default: false,
});
const changePasswordSuccess = defineModel('changePasswordSuccess', {
  default: false,
});
const currentPassword = useField('currentPassword');
const newPassword = useField('newPassword');
const currentPasswordVisible = ref(false);
const newPasswordVisible = ref(false);

const handleChangePassword = handleSubmit((values) => {
  mutate({
    currentPassword: values.currentPassword,
    newPassword: values.newPassword,
  });
});

const onClick = () => {
  changePasswordDialog.value = true;
};
</script>
