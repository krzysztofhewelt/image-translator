import * as yup from 'yup';


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
const translatedText = yup.string().required().min(3).max(65535);

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
  translatedText: translatedText,
});
