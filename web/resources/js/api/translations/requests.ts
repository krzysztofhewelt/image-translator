import axios from 'axios';
import {
  ReOCRAndTranslateRequest,
  TranslateTextRequest,
  UserTranslationsRequest,
} from '@/types/requests/TranslationRequest.ts';
import { Translation } from '@/types/Translation.ts';

export const searchTranslationByTitle = async (
  title: string,
  page: number
): Promise<UserTranslationsRequest> => {
  return await axios
    .get('/translates/search', {
      params: {
        title: title,
        page: page,
      },
    })
    .then((res) => {
      return {
        translations: res.data.data,
        current_page: res.data.current_page,
        last_page: res.data.last_page,
      };
    });
};

export const getTranslation = async (id: number): Promise<Translation> => {
  return await axios.get<Translation>(`/translates/${id}/show`).then((res) => {
    return res.data;
  });
};

export const addTranslation = async (
  image: File,
  sourceLang: string,
  targetLang: string
): Promise<number> => {
  const formData = new FormData();

  formData.append('image', image);
  formData.append('source_lang', sourceLang);
  formData.append('target_lang', targetLang);

  return await axios
    .post('/translates/translate', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    .then((res) => res.data.id);
};

export const updateTranslation = async (
  id: number,
  title: string,
  originalText: string,
  translatedText: string,
  sourceLang: string,
  targetLang: string
): Promise<null> => {
  return await axios.post(`/translates/${id}/update`, {
    title: title,
    original_text: originalText,
    translated_text: translatedText,
    source_lang: sourceLang,
    target_lang: targetLang,
  });
};

export const deleteTranslation = async (id: number): Promise<null> => {
  return await axios.delete(`/translates/${id}/delete`);
};

export const publishTranslation = async (id: number): Promise<number> => {
  return await axios
    .post(`/translates/${id}/publish`)
    .then((res) => res.data.id);
};

export const reOCRAndTranslateImage = async (
  id: number,
  sourceLang: string,
  targetLang: string
): Promise<ReOCRAndTranslateRequest> => {
  return await axios
    .post<ReOCRAndTranslateRequest>(`/translates/${id}/ocr-retranslate`, {
      source_lang: sourceLang,
      target_lang: targetLang,
    })
    .then((res) => {
      return res.data;
    });
};

export const reTranslateImage = async (
  originalText: string,
  sourceLang: string,
  targetLang: string
): Promise<TranslateTextRequest> => {
  return await axios
    .post<TranslateTextRequest>('/translates/text-translate', {
      original_text: originalText,
      source_lang: sourceLang,
      target_lang: targetLang,
    })
    .then((res) => {
      return res.data;
    });
};
