<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTranslationRequest;
use App\Http\Requests\ReRunOCRAndTranslateRequest;
use App\Http\Requests\TranslateTextRequest;
use App\Http\Requests\UpdateTranslationRequest;
use App\Models\Translation;
use GuzzleHttp\Client;
use App\Support\Languages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class TranslateController extends Controller
{
  private Translation $translationModel;

  public function __construct()
  {
    $this->translationModel = new Translation();
  }

  public function translateImage(AddTranslationRequest $request): JsonResponse
  {
    $filename = $this->saveImage($request->file('image'));
    $filepath = Storage::disk('public')->path($filename);
    $sourceLangTesseractCode = $request->source_lang;
    $targetLangTesseractCode = $request->target_lang;

    $sourceLangCode = Languages::findLanguageCodeByTesseractCode(
      $sourceLangTesseractCode
    );
    $targetLangCode = Languages::findLanguageCodeByTesseractCode(
      $targetLangTesseractCode
    );

    $textFromImage = $this->runOCR($filepath, $sourceLangTesseractCode);
    $translatedText = $this->translateTexts(
      $textFromImage,
      $targetLangCode,
      $sourceLangCode
    );
    $formattedOriginalText = implode("\n", $textFromImage);
    $formattedTexts = implode("\n", $translatedText['translatedText']);
    return $this->create(
      $filename,
      $formattedOriginalText,
      $formattedTexts,
      $sourceLangTesseractCode,
      $targetLangTesseractCode
    );
  }

  public function proposeTitleForTranslation(): string
  {
    $nextId = $this->translationModel->countUserTranslations(Auth::id()) + 1;
    return 'Untitled translation ' . $nextId;
  }

  public function saveImage(?UploadedFile $image): ?string
  {
    if ($image === null) {
      return null;
    }

    $filenameHashed =
      pathinfo($image->hashName(), PATHINFO_FILENAME) .
      '.' .
      $image->getClientOriginalExtension();
    return Storage::disk('public')->putFileAs('', $image, $filenameHashed);
  }

  public function runOCR(string $filepath, string $sourceLangTesseractCode)
  {
    $imageOCRScript = dirname(base_path()) . '\tesseract\main.py';

    $sourceLang = $sourceLangTesseractCode;
    if ($sourceLangTesseractCode === 'auto') {
      $sourceLang = '';
    }

    $output = '';

    exec(
      env('PYTHON_INTERPRETER') .
        ' "' .
        $imageOCRScript .
        '" --image-url=' .
        $filepath .
        ' --lang=' .
        $sourceLang,
      $output
    );

    if (empty($output)) {
      return [];
    }

    return file($output[0], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  }

  public function translateTexts(
    array $texts,
    string $targetLangCode,
    string $sourceLangCode = 'auto'
  ): array {
    $client = new Client();

    $response = $client->post(env('TRANSLATE_API') . '/translate', [
      'json' => [
        'q' => $texts,
        'source' => $sourceLangCode,
        'target' => $targetLangCode,
      ],
    ]);

    $response_data = $response->getBody()->getContents();
    return json_decode($response_data, true);
  }

  public function runOCRAndReTranslate(
    ReRunOCRAndTranslateRequest $request,
    int $translationId
  ) {
    $validated = $request->validated();

    $translation = $this->translationModel->find($translationId);
    if ($translation == null) {
      return response()->json(
        ['errors' => ['id' => 'Translation does not exists!']],
        Response::HTTP_NOT_FOUND
      );
    }

    $textFromImage = $this->runOCR(
      Storage::disk('public')->path($translation->image_name->filename),
      $validated['source_lang']
    );

    $translatedText = $this->translateTexts(
      $textFromImage,
      Languages::findLanguageCodeByTesseractCode($validated['target_lang']),
      Languages::findLanguageCodeByTesseractCode($validated['source_lang'])
    );

    $formattedOriginalText = implode("\n", $textFromImage);
    $formattedTexts = implode("\n", $translatedText['translatedText']);

    return response()->json([
      'translated_text' => $formattedTexts,
      'original_text' => $formattedOriginalText,
    ]);
  }

  public function runTranslateText(TranslateTextRequest $request)
  {
    $validated = $request->validated();

    $original_texts = preg_split("/\r\n|\n|\r/", $validated['original_text']);
    $translated_texts = $this->translateTexts(
      $original_texts,
      Languages::findLanguageCodeByTesseractCode($validated['target_lang']),
      Languages::findLanguageCodeByTesseractCode($validated['source_lang'])
    );

    $formattedTranslatedTexts = implode(
      "\n",
      $translated_texts['translatedText']
    );

    return response()->json([
      'translated_text' => $formattedTranslatedTexts,
    ]);
  }

  public function create(
    string $filename,
    string $originalTexts,
    string $translatedTexts,
    string $sourceLang,
    string $targetLang
  ) {
    $translation = $this->translationModel->create([
      'title' => $this->proposeTitleForTranslation(),
      'image_name' => $filename,
      'original_text' => $originalTexts,
      'translated_text' => $translatedTexts,
      'source_lang' => $sourceLang,
      'target_lang' => $targetLang,
      'user_id' => Auth::id(),
    ]);

    return response()->json(
      [
        'success' => 'Translation added successfully',
        'id' => $translation->id,
      ],
      Response::HTTP_CREATED
    );
  }

  public function show(int $translationId)
  {
    $translation = $this->translationModel->find($translationId);
    if ($translation == null) {
      return response()->json(
        ['errors' => ['id' => 'Translation does not exists!']],
        Response::HTTP_NOT_FOUND
      );
    }

    Gate::authorize('manage-translations', $translation);

    return response()->json($translation);
  }

  public function update(UpdateTranslationRequest $request, int $translationId)
  {
    $translation = $this->translationModel->find($translationId);
    if ($translation == null) {
      return response()->json(
        ['errors' => ['id' => 'Translation does not exists!']],
        Response::HTTP_NOT_FOUND
      );
    }

    Gate::authorize('manage-translations', $translation);

    $translation->update($request->validated());

    return response()->json([
      'success' => 'Task updated successfully',
      'id' => $translation->id,
    ]);
  }

  public function delete(int $translationId)
  {
    $translation = $this->translationModel->find($translationId);
    if ($translation == null) {
      return response()->json(
        ['errors' => ['id' => 'Translation does not exists!']],
        Response::HTTP_NOT_FOUND
      );
    }

    Gate::authorize('manage-translations', $translation);

    $translation->delete();

    return response()->json('Translation deleted successfully');
  }

  public function searchTranslationsByTitle(Request $request)
  {
    return response()->json(
      $this->translationModel->searchUserTranslations(
        Auth::id(),
        $request->title
      )
    );
  }

  public function publishTranslation(int $translationId)
  {
    $translation = $this->translationModel->find($translationId);
    if ($translation == null) {
      return response()->json(
        ['errors' => ['id' => 'Translation does not exists!']],
        Response::HTTP_NOT_FOUND
      );
    }

    Gate::authorize('manage-translations', $translation);

    $translation->public = !$translation->public;
    $translation->save();

    return response()->json([
      'public' => $translation->public,
    ]);
  }
}
