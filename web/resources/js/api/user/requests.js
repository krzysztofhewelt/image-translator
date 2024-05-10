import axios from 'axios';

export const changePassword = async (currentPassword, newPassword) => {
  return await axios.post('/change-password', {currentPassword, newPassword});
}
