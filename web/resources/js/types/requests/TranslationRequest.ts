import { Translation } from '@/types/Translation.ts';

export interface UserTranslationsRequest {
  translations: Translation[];
  current_page: number;
  last_page: number;
}

export interface ReOCRAndTranslateRequest {
  original_text: string;
  translated_text: string;
}

export interface TranslateTextRequest {
  translated_text: string;
}
