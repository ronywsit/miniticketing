import axios from "axios";

// Create an Axios instance
const apiClient = axios.create({
  baseURL:
    import.meta.env.VITE_API_BASE_URL || "http://ticketing-backend.test/api",
});

export const setHeaders = (headers = {}) => {
  apiClient.defaults.headers.common = { ...headers };
};

// API helper functions
const apiInstance = {
  get: (url, params = {}, headers = {}) =>
    apiClient.get(url, { params, headers }),

  post: (url, data = {}, headers = {}) =>
    apiClient.post(url, data, { headers }),

  put: (url, data = {}, headers = {}) => apiClient.put(url, data, { headers }),

  delete: (url, headers = {}) => apiClient.delete(url, { headers }),
};

export default apiInstance;
