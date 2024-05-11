import { useMutation } from '@tanstack/vue-query';
import { login, register } from '@/api/authenticate/requests.ts';
import { LoginForm, RegisterForm } from '@/types/forms/Auth.ts';
import { AxiosError } from 'axios';
import { AuthRequest } from '@/types/requests/AuthRequest.ts';

export const useLoginMutation = (onSuccess: (user: AuthRequest) => void) => {
  return useMutation<AuthRequest, AxiosError, LoginForm>({
    mutationFn: (values: LoginForm) => login(values.email, values.password),
    onSuccess: onSuccess,
  });
};

export const useRegisterMutation = (onSuccess: (user: AuthRequest) => void) => {
  return useMutation<AuthRequest, AxiosError, RegisterForm>({
    mutationFn: (values: RegisterForm) =>
      register(values.username, values.email, values.password),
    onSuccess: onSuccess,
  });
};
