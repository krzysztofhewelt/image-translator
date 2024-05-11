import axios from 'axios';
import router from '@/router/index.ts';
import { loadToken, saveToken } from '@/utils/authentication.ts';

export default function setup() {
  axios.defaults.baseURL = import.meta.env.BASE_API_URL;
  axios.defaults.headers.common['Authorization'] = 'Bearer ' + loadToken();
  axios.defaults.headers.post['Content-Type'] = 'application/json';

  axios.interceptors.response.use(
    (response) => {
      return response;
    },
    async (error) => {
      if (!error.response) {
        return Promise.reject(error);
      }

      if (
        error.response.status === 401 &&
        error.response.config.url !== '/login' &&
        error.response.config.url !== '/refresh'
      ) {
        return axios.post('/refresh')
          .then((res) => {
            saveToken(res.data.token)

            error.config.headers = {
              Authorization: `Bearer ${loadToken()}`
            };

            error.config.data = error.config.data ? JSON.parse(error.config.data) : '';

            return axios(error.config);
          })
          .catch((error) => {
            if (error.response.status === 401) {
              return router.push('/login');
            }

            throw error;
          });
      }

      if (error.response.status === 403) {
        return router.push('/');
      }

      if (error.response.status === 404) {
        return router.push('/');
      }

      if (error.response.status === 500) {
        return router.push('/');
      }

      return Promise.reject(error);
    }
  );
}
