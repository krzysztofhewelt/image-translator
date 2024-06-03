<?php

use App\Rules\AvailableLanguage;

return [
  'image' => ['required', 'image', 'max:10000'],
  'source_lang' => ['required', new AvailableLanguage],
  'target_lang' => ['required', new AvailableLanguage],
  'original_text' => ['required', 'min:3', 'max:65535'],
  'translated_text' => ['required', 'min:3', 'max:65535'],
  'title' => ['required', 'min:3', 'max:255'],
  'public' => ['required', 'boolean'],

  'username' => ['required', 'min:3', 'max:50', 'unique:users,username'],
  'email' => ['required', 'email'],
  'password' => [
    'required',
    'regex:/^.*(?=.{1,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).{8,255}$/',
  ],
];
