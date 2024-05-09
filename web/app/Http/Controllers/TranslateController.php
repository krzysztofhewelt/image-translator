<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageTranslationRequest;
use App\Http\Requests\TranslationRequest;
use App\Models\Translation;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class TranslateController extends Controller
{
  private Translation $translationModel;

  public function __construct()
  {
    $this->translationModel = new Translation();
  }

  public function translateImage(ImageTranslationRequest $request): JsonResponse
  {
    $filename = $this->saveImage($request->file('image'));
    $filepath = Storage::disk('images')->path($filename);
    $textFromImage = $this->runOCR($filepath);
    $translatedText = $this->translateTexts($textFromImage, $request->target, $request->source);
    $formattedTexts = implode("\n", $translatedText['translatedText']);
    return $this->create($filename, $formattedTexts);
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
    return Storage::disk('images')->putFileAs('', $image, $filenameHashed);
  }

  public function runOCR(string $filepath)
  {
    $imageOCRScript = dirname(base_path()) . '\tesseract\main.py';
    $output = '';
    exec(
      env('PYTHON_INTERPRETER') .
        ' "' .
        $imageOCRScript .
        '" --image-url=' .
        $filepath,
      $output
    );

    $output = array_map(function ($line) {
      return mb_convert_encoding($line, 'UTF-8', 'UTF-8');
    }, $output);

    return $output;
  }

  public function translateTexts(
    array $texts,
    string $target,
    string $source = 'auto'
  ): array
  {
    $client = new Client();

    $response = $client->post(env('TRANSLATE_API') . '/translate', [
      'json' => [
        'q' => $texts,
        'source' => $source,
        'target' => $target,
      ],
    ]);

    $response_data = $response->getBody()->getContents();
    return json_decode($response_data, true);
  }

  public function create(string $filename, string $translatedTexts)
  {
    $translation = $this->translationModel->create([
      'title' => $this->proposeTitleForTranslation(),
      'image_name' => $filename,
      'translated_text' => $translatedTexts,
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
        ['error' => 'Translation does not exists!'],
        Response::HTTP_NOT_FOUND
      );
    }

    return response()->json($translation);
  }

  public function update(TranslationRequest $request, int $translationId)
  {
    $translation = $this->translationModel->find($translationId);
    if ($translation == null) {
      return response()->json(
        ['error' => 'Translation does not exists!'],
        Response::HTTP_NOT_FOUND
      );
    }

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
        ['error' => 'Translation does not exists!'],
        Response::HTTP_NOT_FOUND
      );
    }

    $translation->delete();

    return response()->json('Translation deleted successfully');
  }

  public function searchTranslationsByTitle(Request $request)
  {
    return response()->json(
      $this->translationModel->searchUserTranslations(Auth::id(), $request->q)
    );
  }

  public function publishTranslation(int $translationId)
  {
    $translation = $this->translationModel->find($translationId);
    if ($translation == null) {
      return response()->json(
        ['error' => 'Translation does not exists!'],
        Response::HTTP_NOT_FOUND
      );
    }

    $translation->public = true;
    $translation->save();

    return response()->json('Translation published successfully');
  }
}
