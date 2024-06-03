<?php
declare(strict_types=1);

namespace App\Support;

class Languages
{
  public static array $languages = [
    ['code' => 'auto', 'name' => 'auto', 'tesseractCode' => 'auto'],
    ['code' => 'en', 'name' => 'English', 'tesseractCode' => 'eng'],
    ['code' => 'sq', 'name' => 'Albanian', 'tesseractCode' => 'sqi'],
    ['code' => 'ar', 'name' => 'Arabic', 'tesseractCode' => 'ara'],
    ['code' => 'az', 'name' => 'Azerbaijani', 'tesseractCode' => 'aze'],
    ['code' => 'bn', 'name' => 'Bengali', 'tesseractCode' => 'ben'],
    ['code' => 'bg', 'name' => 'Bulgarian', 'tesseractCode' => 'bul'],
    ['code' => 'ca', 'name' => 'Catalan', 'tesseractCode' => 'cat'],
    ['code' => 'zh', 'name' => 'Chinese', 'tesseractCode' => 'chi_sim'],
    [
      'code' => 'zt',
      'name' => 'Chinese (traditional)',
      'tesseractCode' => 'chi_tra',
    ],
    ['code' => 'cs', 'name' => 'Czech', 'tesseractCode' => 'ces'],
    ['code' => 'da', 'name' => 'Danish', 'tesseractCode' => 'dan'],
    ['code' => 'nl', 'name' => 'Dutch', 'tesseractCode' => 'nld'],
    ['code' => 'eo', 'name' => 'Esperanto', 'tesseractCode' => 'epo'],
    ['code' => 'et', 'name' => 'Estonian', 'tesseractCode' => 'est'],
    ['code' => 'fi', 'name' => 'Finnish', 'tesseractCode' => 'fin'],
    ['code' => 'fr', 'name' => 'French', 'tesseractCode' => 'fra'],
    ['code' => 'de', 'name' => 'German', 'tesseractCode' => 'deu'],
    ['code' => 'el', 'name' => 'Greek', 'tesseractCode' => 'ell'],
    ['code' => 'he', 'name' => 'Hebrew', 'tesseractCode' => 'heb'],
    ['code' => 'hi', 'name' => 'Hindi', 'tesseractCode' => 'hin'],
    ['code' => 'hu', 'name' => 'Hungarian', 'tesseractCode' => 'hun'],
    ['code' => 'id', 'name' => 'Indonesian', 'tesseractCode' => 'ind'],
    ['code' => 'ga', 'name' => 'Irish', 'tesseractCode' => 'gle'],
    ['code' => 'it', 'name' => 'Italian', 'tesseractCode' => 'ita'],
    ['code' => 'ja', 'name' => 'Japanese', 'tesseractCode' => 'jpn'],
    ['code' => 'ko', 'name' => 'Korean', 'tesseractCode' => ''],
    ['code' => 'lv', 'name' => 'Latvian', 'tesseractCode' => 'lav'],
    ['code' => 'lt', 'name' => 'Lithuanian', 'tesseractCode' => 'lit'],
    ['code' => 'ms', 'name' => 'Malay', 'tesseractCode' => 'msa'],
    ['code' => 'nb', 'name' => 'Norwegian', 'tesseractCode' => 'nor'],
    ['code' => 'fa', 'name' => 'Persian', 'tesseractCode' => 'fas'],
    ['code' => 'pl', 'name' => 'Polish', 'tesseractCode' => 'pol'],
    ['code' => 'pt', 'name' => 'Portuguese', 'tesseractCode' => 'por'],
    ['code' => 'ro', 'name' => 'Romanian', 'tesseractCode' => 'ron'],
    ['code' => 'ru', 'name' => 'Russian', 'tesseractCode' => 'rus'],
    ['code' => 'sk', 'name' => 'Slovak', 'tesseractCode' => 'slk'],
    ['code' => 'sl', 'name' => 'Slovenian', 'tesseractCode' => 'slv'],
    ['code' => 'es', 'name' => 'Spanish', 'tesseractCode' => 'spa'],
    ['code' => 'sv', 'name' => 'Swedish', 'tesseractCode' => 'swe'],
    ['code' => 'tl', 'name' => 'Tagalog', 'tesseractCode' => 'tgl'],
    ['code' => 'th', 'name' => 'Thai', 'tesseractCode' => 'tha'],
    ['code' => 'tr', 'name' => 'Turkish', 'tesseractCode' => 'tur'],
    ['code' => 'uk', 'name' => 'Ukranian', 'tesseractCode' => 'urk'],
    ['code' => 'ur', 'name' => 'Urdu', 'tesseractCode' => 'urd'],
  ];

  public static function findLanguageCodeByTesseractCode(
    string $tesseractCode
  ): string {
    foreach (self::$languages as $language) {
      if ($language['tesseractCode'] === $tesseractCode) {
        return $language['code'];
      }
    }

    return '';
  }
}
