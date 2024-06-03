import * as yup from 'yup';
import { languages } from '@/utils/languages';

const email = yup.string().required().email();
const password = yup
  .string()
  .required()
  .min(8)
  .max(128)
  .matches(/[a-z]+/)
  .matches(/[A-Z]+/)
  .matches(/[@$!%*#?&]+/)
  .matches(/\d+/);
const username = yup.string().required().min(3).max(50).notOneOf(['admin']);
const title = yup.string().required().min(3).max(255);
const translateText = yup.string().required().min(3).max(65535);
const sourceLang = yup
  .mixed<string>()
  .required()
  .test('isAvailableLanguage', 'This language is not supported', (value) => {
    return !!languages.find((o) => o.tesseractCode === value);
  });
const targetLang = yup
  .mixed<string>()
  .required()
  .test('isAvailableLanguage', 'This language is not supported', (value) => {
    return value !== 'auto' && !!languages.find((o) => o.tesseractCode === value);
  });

export const loginSchema = yup.object().shape({
  email: email,
  password: password,
});

export const registerSchema = yup.object().shape({
  username: username,
  email: email,
  password: password,
});

export const changePasswordSchema = yup.object().shape({
  currentPassword: password,
  newPassword: password.notOneOf([yup.ref('currentPassword')]),
});

export const editTranslationSchema = yup.object().shape({
  title: title,
  originalText: translateText,
  translatedText: translateText,
});

export const translateTextSchema = yup.object().shape({
  originalText: translateText,
  sourceText: sourceLang,
  targetText: targetLang,
});

export const addTranslationSchema = yup.object().shape({
  image: yup
    .mixed<File>()
    .required()
    .test(
      'fileFormat',
      'Only image formats supported: png, jpeg, jpg, bmp, pnm, tiff, webp, gif',
      (value) => {
        if (value) {
          const supportedFormats = [
            'png',
            'jpeg',
            'jpg',
            'bmp',
            'pnm',
            'tiff',
            'webp',
            'gif',
          ];
          const fileExtension = value.name.split('.').pop();

          return supportedFormats.includes(fileExtension || '');
        }
      }
    )
    .test('fileSize', 'File size must not be more than 10 MB', (value) => {
      if (value) {
        return value.size <= 10000000;
      }
    }),
  sourceLang: sourceLang,
  targetLang: targetLang,
});
