<template>
  <v-overlay
    :model-value="
      isLoading ||
      isLoadingPublish ||
      isPendingUpdate ||
      isPendingTranslateText ||
      isPendingReOCRAndTranslate
    "
    class="align-center justify-center"
    persistent
  >
    <v-progress-circular
      color="primary"
      size="64"
      indeterminate
    ></v-progress-circular>
  </v-overlay>

  <v-snackbar color="green" v-model="updatedSuccessfully" :timeout="2000">
    Updated successfully
  </v-snackbar>

  <v-snackbar v-model="copiedSuccessfully" :timeout="2000">
    Copied URL successfully
  </v-snackbar>

  <v-container>
    <div class="rounded-xl bg-grey-lighten-4 overflow-hidden">
      <form @submit.prevent="handleUpdate">
        <v-text-field
          variant="solo-filled"
          v-model="title.value.value"
          label="Title"
        ></v-text-field>

        <div class="d-flex justify-space-between align-center px-5 pb-4">
          <v-switch
            v-model="showOriginalText"
            color="primary"
            label="Original text"
            hide-details
          ></v-switch>
          <div class="d-inline-flex align-center ga-2">
            <v-autocomplete
              variant="solo-filled"
              style="width: 200px"
              label="Source language"
              v-model="sourceLang.value.value"
              :items="languages"
              item-value="tesseractCode"
              item-text="name"
              item-title="name"
              hide-details
              @update:model-value="reOCRAndTranslate"
            ></v-autocomplete>

            <v-icon
              icon="mdi-swap-horizontal"
              size="48"
              @click="handlePublish"
              class="cursor-pointer"
            ></v-icon>

            <v-autocomplete
              variant="solo-filled"
              style="width: 200px"
              label="Target language"
              v-model="targetLang.value.value"
              :items="languages.filter((el) => el.code !== 'auto')"
              item-value="tesseractCode"
              item-text="name"
              item-title="name"
              hide-details
              @update:model-value="translateTexts"
            ></v-autocomplete>
          </div>

          <div class="d-inline-flex ga-4">
            <v-btn
              v-show="!published"
              prepend-icon="mdi-publish-off"
              color="grey-lighten-2"
              @click="handlePublish"
              >Private</v-btn
            >
            <v-btn
              @click="handlePublish"
              v-show="published"
              color="green"
              prepend-icon="mdi-publish"
              >Public</v-btn
            >

            <v-btn
              prepend-icon="mdi-link-variant"
              v-show="published"
              @click="copyTranslationURLToClipboard"
            >
              Get link
            </v-btn>
          </div>
        </div>

        <v-divider></v-divider>

        <div class="d-flex pa-5 align-stretch">
          <div class="h-75 mr-6" style="width: 40%">
            <v-img
              width="100%"
              height="100%"
              :src="`/storage/${data?.image_name.filename}`"
            ></v-img>
          </div>

          <div class="h-100 mr-6 flex-1-1" v-show="showOriginalText">
            <v-textarea
              label="Original text"
              v-model="originalText.value.value"
              auto-grow
              max-rows="25"
            ></v-textarea>
          </div>
          <div class="h-100 flex-1-1">
            <v-textarea
              label="Translated text"
              v-model="translatedText.value.value"
              auto-grow
              max-rows="25"
            ></v-textarea>
          </div>
        </div>

        <v-divider></v-divider>

        <div class="d-flex flex-row-reverse pa-4 ga-4">
          <v-btn type="submit" color="blue">Save</v-btn>

          <DeleteTranslationConfirmDialog
            :id="Number(route.params.id)"
            @on-success="onDeleteSuccess"
          >
            <template v-slot:activator="{ onClick }">
              <v-btn color="red" @click="onClick">Delete</v-btn>
            </template>
          </DeleteTranslationConfirmDialog>
        </div>
      </form>
    </div>
  </v-container>
</template>
<script setup lang="ts">
import { languages } from '@/utils/languages.ts';
import {
  useGetTranslationQuery,
  usePublishTranslationMutation,
  useReOCRAndTranslateImageMutation,
  useTranslateTextMutation,
  useUpdateTranslationMutation,
} from '@/api/translations/hooks.ts';
import { useRoute } from 'vue-router';
import { ref, watchEffect } from 'vue';
import { useField, useForm } from 'vee-validate';
import { editTranslationSchema } from '@/consts/validationSchemas.ts';
import { UpdateTranslateForm } from '@/types/forms/TranslateForm.ts';
import DeleteTranslationConfirmDialog from '@/components/DeleteTranslationConfirmDialog.vue';
import router from '@/router';

const route = useRoute();
const { data, isSuccess, isLoading } = useGetTranslationQuery(
  Number(route.params.id)
);

const { mutate: mutateUpdate, isPending: isPendingUpdate } =
  useUpdateTranslationMutation(() => {
    updatedSuccessfully.value = true;
  });

const {
  data: dataReOCRAndTranslate,
  mutate: mutateReOCRAndTranslate,
  isPending: isPendingReOCRAndTranslate,
} = useReOCRAndTranslateImageMutation((data) => {
  setValues({
    translatedText: data.translated_text,
    originalText: data.original_text,
  });
});

const {
  data: dataTranslateText,
  mutate: mutateTranslateText,
  isPending: isPendingTranslateText,
} = useTranslateTextMutation((data) => {
  setValues({
    translatedText: data,
  });
});

const {
  mutate: mutatePublish,
  data: dataPublish,
  isSuccess: isSuccessPublish,
  isPending: isLoadingPublish,
} = usePublishTranslationMutation(() => {
  console.log('test');
});

const { setValues, values } = useForm<UpdateTranslateForm>({
  validationSchema: editTranslationSchema,
});

const translateTexts = () => {
  mutateTranslateText({
    originalText: values.originalText,
    targetLang: values.targetLang,
    sourceLang: values.sourceLang,
  });
};

const reOCRAndTranslate = () => {
  mutateReOCRAndTranslate({
    id: Number(route.params.id),
    sourceLang: values.sourceLang,
    targetLang: values.targetLang,
  });
};

const handlePublish = () => {
  mutatePublish(Number(route.params.id));
};

const originalText = useField('originalText');
const translatedText = useField('translatedText');
const title = useField('title');
const sourceLang = useField('sourceLang');
const targetLang = useField('targetLang');
const published = ref(0);
const showOriginalText = ref(false);
const updatedSuccessfully = defineModel('updatedSuccessfully', {
  default: false,
});
const copiedSuccessfully = defineModel('copiedSuccessfully', {
  default: false,
});

// Watch for changes in the retrieved data and update the reactive variable
watchEffect(() => {
  if (isSuccess.value) {
    setValues({
      title: data.value?.title,
      originalText: data.value?.original_text,
      translatedText: data.value?.translated_text,
      sourceLang: data.value?.source_lang,
      targetLang: data.value?.target_lang,
    });

    published.value = Number(data.value?.public);
  }

  if (isSuccessPublish.value) {
    published.value = Number(dataPublish.value);
  }
});

const handleUpdate = () => {
  mutateUpdate({
    id: Number(route.params.id),
    title: values.title,
    originalText: values.originalText,
    translatedText: values.translatedText,
    sourceLang: values.sourceLang,
    targetLang: values.targetLang,
    public: published.value.toString(),
  });
};

const copyTranslationURLToClipboard = async () => {
  await navigator.clipboard.writeText(window.location.href);
  copiedSuccessfully.value = true;
};

const onDeleteSuccess = () => {
  router.push('/');
};
</script>
