import axios from 'axios';
import { AuthRequest } from '@/types/requests/AuthRequest.ts';

export const login = async (
  email: string,
  password: string
): Promise<AuthRequest> => {
  return await axios
    .post<AuthRequest>('/login', {
      email: email,
      password: password,
    })
    .then((res) => {
      return res.data;
    });
};

export const register = async (
  username: string,
  email: string,
  password: string
): Promise<AuthRequest> => {
  return await axios
    .post<AuthRequest>('/register', {
      username: username,
      email: email,
      password: password,
    })
    .then((res) => {
      return res.data;
    });
};
