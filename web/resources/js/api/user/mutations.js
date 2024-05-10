import { useMutation } from '@tanstack/vue-query';
import { changePassword } from '@/api/user/requests.js';

export const useChangePasswordMutation = () => {
  return useMutation({
    mutationFn: (values) =>
      changePassword(values.currentPassword, values.newPassword),
  });
};
