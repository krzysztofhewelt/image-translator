export interface UpdateTranslateFormErrors {
  errors: {
    id: Array<string>;
    title: Array<string>;
    original_text: Array<string>;
    translated_text: Array<string>;
    source_lang: Array<string>;
    target_lang: Array<string>;
  };
}

export interface AddTranslateFormErrors {
  errors: {
    image: Array<string>;
    source_lang: Array<string>;
    target_lang: Array<string>;
  };
}

export interface TranslateTextFormErrors {
  original_text: Array<string>;
  target_lang: Array<string>;
  source_lang: Array<string>;
}

export interface ReOCRAndTranslateFormErrors {
  errors: {
    id: Array<string>;
    target_lang: Array<string>;
    source_lang: Array<string>;
  };
}
