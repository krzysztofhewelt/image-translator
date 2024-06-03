<template>
  <slot name="activator" :onClick="onClick"></slot>

  <v-snackbar v-model="deleteTranslationSuccess" :timeout="2000" color="green">
    Translation deleted successfully
  </v-snackbar>

  <v-dialog
    max-width="500"
    v-model="confirmDeleteTranslationDialog"
    style="z-index: 2000"
    :persistent="isPending"
  >
    <v-card
      prepend-icon="mdi-translate"
      title="Delete translation"
      :loading="isPending"
      :disabled="isPending"
    >
      <v-card-text>
        Are you sure you want to delete this translation? This action cannot be
        undone.
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>

        <v-btn
          :loading="isPending"
          text="Close"
          variant="plain"
          @click="confirmDeleteTranslationDialog = false"
        ></v-btn>

        <v-btn
          :loading="isPending"
          text="Delete"
          variant="elevated"
          color="red-darken-3"
          @click="handleDelete"
        ></v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { useDeleteTranslationMutation } from '@/api/translations/hooks.ts';
import { ref } from 'vue';

const confirmDeleteTranslationDialog = defineModel(
  'confirmDeleteTranslationDialog',
  { default: false }
);

const props = defineProps({
  id: {
    type: Number,
    required: true,
  },
});

const emit = defineEmits(['onSuccess']);

const deleteTranslationSuccess = ref(false);
const { mutate, isPending, isSuccess } = useDeleteTranslationMutation(props.id);

const handleDelete = async () => {
  mutate();

  if (isSuccess) {
    confirmDeleteTranslationDialog.value = false;
    deleteTranslationSuccess.value = true;
    emit('onSuccess');
  }
};

const onClick = (event: Event) => {
  event.stopPropagation();
  confirmDeleteTranslationDialog.value = true;
};
</script>
