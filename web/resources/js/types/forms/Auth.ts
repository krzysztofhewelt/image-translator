export interface LoginForm {
  email: string;
  password: string;
}

export interface RegisterForm {
  username: string;
  email: string;
  password: string;
}

export interface ChangePasswordForm {
  currentPassword: string;
  newPassword: string;
}
