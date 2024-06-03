import { useMutation } from '@tanstack/vue-query';
import { changePassword } from '@/api/user/requests.ts';
import { AxiosError } from 'axios';
import { ChangePasswordForm } from '@/types/forms/Auth.ts';
import { ChangePasswordFormErrors } from '@/types/forms/Auth.errors.ts';

export const useChangePasswordMutation = (onSuccess?: () => void) => {
  return useMutation<null, AxiosError<ChangePasswordFormErrors>, ChangePasswordForm>({
    mutationFn: (values: ChangePasswordForm) =>
      changePassword(values.currentPassword, values.newPassword),
    onSuccess: onSuccess,
  });
};
