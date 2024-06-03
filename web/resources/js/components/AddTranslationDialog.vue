<template>
  <slot name="activator" :onClick="onClick"></slot>

  <v-dialog max-width="500" v-model="addTranslateDialog">
    <form @submit.prevent="handleAddTranslation">
      <v-card prepend-icon="mdi-translate" title="Add new translation">
        <v-card-text>
          <div>
            <v-file-input
              v-model="image.value.value"
              prepend-icon=""
              prepend-inner-icon="mdi-cloud-upload-outline"
              :center-affix="true"
              label="Image to translate"
              @update:model-value="previewImage"
              :show-size="true"
              :error-messages="image.errorMessage.value"
            >
            </v-file-input>
          </div>

          <div class="d-flex align-center">
            <v-autocomplete
              label="Source language"
              v-model="sourceLang.value.value"
              :items="languages"
              item-value="tesseractCode"
              item-text="name"
              item-title="name"
              :error-messages="sourceLang.errorMessage.value"
            ></v-autocomplete>

            <v-icon icon="mdi-arrow-right" size="42"></v-icon>

            <v-autocomplete
              label="Target language"
              v-model="targetLang.value.value"
              :items="languages"
              item-value="tesseractCode"
              item-text="name"
              item-title="name"
              :error-messages="targetLang.errorMessage.value"
            ></v-autocomplete>
          </div>
          <v-img v-if="imageUrl" :src="imageUrl" height="450"></v-img>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            :loading="isPending"
            text="Close"
            variant="plain"
            @click="addTranslateDialog = false"
          ></v-btn>

          <v-btn
            type="submit"
            :loading="isPending"
            text="Translate"
            variant="elevated"
            color="green-darken-3"
            @click="handleSubmit"
          ></v-btn>
        </v-card-actions>
      </v-card>
    </form>
  </v-dialog>
</template>

<script setup lang="ts">
import { languages } from '@/utils/languages.ts';
import { useField, useForm } from 'vee-validate';
import { ref } from 'vue';
import { addTranslationSchema } from '@/consts/validationSchemas.ts';
import { AddTranslateForm } from '@/types/forms/TranslateForm.ts';
import { useAddTranslationMutation } from '@/api/translations/hooks.ts';
import router from '@/router';

const addTranslateDialog = defineModel('addTranslateDialog', {
  default: false,
});

const { handleSubmit } = useForm<AddTranslateForm>({
  validationSchema: addTranslationSchema,
});

const { mutate, isPending } = useAddTranslationMutation((id: number) => {
  router.push({ name: 'TranslationsEdit', params: { id: id }})
});

const image = useField<File>('image');
const sourceLang = useField('sourceLang');
const targetLang = useField('targetLang');
const imageUrl = ref('');

const previewImage = (e: File | File[]) => {
  const singleFile = Array.isArray(e) ? e[0] : e;

  if (singleFile) {
    imageUrl.value = URL.createObjectURL(singleFile);
  } else {
    imageUrl.value = '';
  }
};

const handleAddTranslation = handleSubmit((values) => {
  console.log(values);

  mutate({
    image: values.image,
    sourceLang: values.sourceLang,
    targetLang: values.targetLang,
  });
});

const onClick = () => {
  addTranslateDialog.value = true;
};
</script>
