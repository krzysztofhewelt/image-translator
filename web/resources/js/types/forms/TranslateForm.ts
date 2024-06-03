export interface UpdateTranslateForm {
  id: number;
  title: string;
  originalText: string;
  public: string;
  translatedText: string;
  sourceLang: string;
  targetLang: string;
}

export interface AddTranslateForm {
  image: File;
  sourceLang: string;
  targetLang: string;
}

export interface TranslateTextForm {
  originalText: string;
  targetLang: string;
  sourceLang: string;
}


export interface ReOCRAndTranslateForm {
  id: number;
  targetLang: string;
  sourceLang: string;
}
