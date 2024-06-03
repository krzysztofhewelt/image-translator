export interface Translation {
  id: number;
  title: string;
  image_name: {
    filename: string;
    real_path: string;
  };
  original_text: string;
  translated_text: string;
  source_lang: string;
  target_lang: string;
  public: number;
  created_at: Date;
  updated_at: Date;
}
