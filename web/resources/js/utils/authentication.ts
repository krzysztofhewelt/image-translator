import { User } from '@/types/User.ts';

export const loadToken = () => {
  return localStorage.getItem('token') || '';
};

export const loadUser = () => {
  try {
    return JSON.parse(localStorage.getItem('user') || '{}');
  } catch (e) {
    return {};
  }
};

export const saveUser = (user: User) => {
  localStorage.setItem('user', JSON.stringify(user));
};

export const saveToken = (token: string) => {
  localStorage.setItem('token', token);
};

export const deleteUserAndToken = () => {
  localStorage.clear();
};

export const getUserData = (): User => {
  const storageUser = loadUser();
  return {
    id: storageUser.id,
    username: storageUser.username,
    email: storageUser.email,
  };
};
