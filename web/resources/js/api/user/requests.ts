import axios from 'axios';

export const changePassword = async (
  currentPassword: string,
  newPassword: string
): Promise<null> => {
  return await axios.post('/change-password', {
    current_password: currentPassword,
    new_password: newPassword,
  });
};
