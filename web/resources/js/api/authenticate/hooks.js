import { useMutation } from '@tanstack/vue-query';
import { login } from '@/api/authenticate/requests.js';

export const useLoginMutation = (onSuccess) => {
  return useMutation({
    mutationFn: (values) => login(values.login, values.password),
    onSuccess: onSuccess
  })
};
