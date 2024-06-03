import { Translation } from '@/types/Translation.ts';

export interface TranslationRequest {
  translations: Translation[];
  current_page: number;
  last_page: number;
}
