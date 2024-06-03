import {
  addTranslation,
  deleteTranslation,
  getTranslation,
  publishTranslation,
  reOCRAndTranslateImage,
  reTranslateImage,
  searchTranslationByTitle,
  updateTranslation,
} from '@/api/translations/requests.ts';
import { useMutation, useQuery } from '@tanstack/vue-query';
import { Translation } from '@/types/Translation.ts';
import { AxiosError } from 'axios';
import {
  AddTranslateForm,
  ReOCRAndTranslateForm,
  TranslateTextForm,
  UpdateTranslateForm,
} from '@/types/forms/TranslateForm.ts';
import { TranslationRequest } from '@/types/requests/TranslationRequest.ts';
import { ModelRef } from 'vue';

export const useUpdateTranslationMutation = (onSuccess: () => void) => {
  return useMutation<null, AxiosError, UpdateTranslateForm>({
    mutationFn: (values: UpdateTranslateForm) =>
      updateTranslation(
        values.id,
        values.title,
        values.originalText,
        values.translatedText,
        values.sourceLang,
        values.targetLang
      ),
    onSuccess: onSuccess,
  });
};

export const useAddTranslationMutation = (onSuccess: (id: number) => void) => {
  return useMutation<number, AxiosError, AddTranslateForm>({
    mutationFn: (values: AddTranslateForm) =>
      addTranslation(values.image, values.sourceLang, values.targetLang),
    onSuccess: onSuccess,
  });
};

export const useSearchTranslationByTitleQuery = (
  title: ModelRef<string>,
  page: ModelRef<number>
) => {
  return useQuery<TranslationRequest>({
    queryKey: ['translationsList', title],
    queryFn: () => searchTranslationByTitle(title.value, page.value),
    enabled: false,
  });
};

export const useGetTranslationQuery = (id: number) => {
  return useQuery<Translation>({
    queryKey: ['translationGet', id],
    queryFn: () => getTranslation(id),
  });
};

export const useDeleteTranslationMutation = (id: number) => {
  return useMutation<null>({
    mutationKey: ['translationDelete', id],
    mutationFn: () => deleteTranslation(id),
  });
};

export const usePublishTranslationMutation = (
  onSuccess: () => void,
) => {
  return useMutation<number, AxiosError, number>({
    mutationFn: (values: number) => publishTranslation(values),
    onSuccess: onSuccess,
  });
};

export const useReOCRAndTranslateImageMutation = (
  onSuccess: (data) => void
) => {
  return useMutation<any, AxiosError, ReOCRAndTranslateForm>({
    mutationFn: (values) =>
      reOCRAndTranslateImage(values.id, values.sourceLang, values.targetLang),
    onSuccess: onSuccess,
  });
};

export const useTranslateTextMutation = (onSuccess: () => void) => {
  return useMutation<string, AxiosError, TranslateTextForm>({
    mutationFn: (values: TranslateTextForm) =>
      reTranslateImage(
        values.originalText,
        values.sourceLang,
        values.targetLang
      ),
    onSuccess: onSuccess,
  });
};
