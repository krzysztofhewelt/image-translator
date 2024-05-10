export const loadToken = () => {
  return localStorage.getItem('token') || '';
};

export const loadUser = () => {
  return JSON.parse(localStorage.getItem('user')) || {};
};
