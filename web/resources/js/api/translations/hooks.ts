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
import {
  UserTranslationsRequest,
  ReOCRAndTranslateRequest,
  TranslateTextRequest,
} from '@/types/requests/TranslationRequest.ts';
import { ModelRef } from 'vue';
import {
  AddTranslateFormErrors,
  ReOCRAndTranslateFormErrors,
  TranslateTextFormErrors,
  UpdateTranslateFormErrors,
} from '@/types/forms/TranslateForm.errors.ts';

export const useSearchTranslationByTitleQuery = (
  title: ModelRef<string>,
  page: ModelRef<number>
) => {
  return useQuery<UserTranslationsRequest>({
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

export const useUpdateTranslationMutation = (onSuccess?: () => void) => {
  return useMutation<
    null,
    AxiosError<UpdateTranslateFormErrors>,
    UpdateTranslateForm
  >({
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

export const useAddTranslationMutation = (onSuccess?: (id: number) => void) => {
  return useMutation<
    number,
    AxiosError<AddTranslateFormErrors>,
    AddTranslateForm
  >({
    mutationFn: (values: AddTranslateForm) =>
      addTranslation(values.image, values.sourceLang, values.targetLang),
    onSuccess: onSuccess,
  });
};

export const useDeleteTranslationMutation = (
  id: number,
  onSuccess?: () => void
) => {
  return useMutation<null, AxiosError>({
    mutationKey: ['translationDelete', id],
    mutationFn: () => deleteTranslation(id),
    onSuccess: onSuccess,
  });
};

export const usePublishTranslationMutation = (onSuccess?: () => void) => {
  return useMutation<number, AxiosError, number>({
    mutationFn: (id: number) => publishTranslation(id),
    onSuccess: onSuccess,
  });
};

export const useReOCRAndTranslateImageMutation = (
  onSuccess?: (data: ReOCRAndTranslateRequest) => void
) => {
  return useMutation<
    ReOCRAndTranslateRequest,
    AxiosError<ReOCRAndTranslateFormErrors>,
    ReOCRAndTranslateForm
  >({
    mutationFn: (values) =>
      reOCRAndTranslateImage(values.id, values.sourceLang, values.targetLang),
    onSuccess: onSuccess,
  });
};

export const useTranslateTextMutation = (onSuccess?: (data: TranslateTextRequest) => void) => {
  return useMutation<
    TranslateTextRequest,
    AxiosError<TranslateTextFormErrors>,
    TranslateTextForm
  >({
    mutationFn: (values: TranslateTextForm) =>
      reTranslateImage(
        values.originalText,
        values.sourceLang,
        values.targetLang
      ),
    onSuccess: onSuccess,
  });
};
