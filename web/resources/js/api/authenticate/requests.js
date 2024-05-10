import axios from 'axios';

export const refreshJWTToken = async () => {
  return await axios.post('/refresh','').then((res) => {
    return res;
  })
}

export const login = async (email, password) => {
  return await axios.post('/login', {
    "email": email,
    "password": password
  }).then((res) => {
    return res;
  })
}

