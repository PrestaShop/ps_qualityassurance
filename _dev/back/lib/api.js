import axios from 'axios';
import 'regenerator-runtime/runtime';

const {urls} = window.qualityAssurance;

/**
 * Abstract function that make a GET request to the DBA API
 * @param {string} path
 * @returns {Promise}
 */
const makeGetRequest = (url, params = {}) => new Promise((resolve, reject) => {
  const config = {
    headers: {
      Accept: 'application/json',
    },
    withCredentials: true,
    params,
  };
  axios.get(url, config).then((result) => {
    resolve(result);
  }).catch((error) => {
    reject(error);
  });
});

/**
 * Abstract function that make a POST request to the DBA API
 * @param {string} path
 * @param {object} postData
 * @returns {Promise}
 */
const makePostRequest = (url, postData, config = {}) => new Promise((resolve, reject) => {
  const newConfig = {
    headers: {
      Accept: 'application/json',
    },
    withCredentials: true,
    ...config,
  };

  const form = new FormData();

  Object.entries(postData).forEach((entry) => {
    const [key, value] = entry;
    form.append(key, value);
  });

  axios.post(url, form, newConfig).then((result) => {
    resolve(result);
  }).catch((error) => {
    reject(error);
  });
});


const api = {
  registerHook(postData) {
    return makePostRequest(urls.register, postData);
  },
  deleteHook(hookId) {
    return makePostRequest(urls.delete, {hookId});
  },
  updateHook(postData) {
    return makePostRequest(urls.update, postData);
  },
  toggleHookStatus(hookId) {
    return makePostRequest(urls.toggleHookStatus, {hookId});
  },
  getHooks() {
    return makeGetRequest(urls.hooks);
  },
  getRegisteredHooks() {
    return makeGetRequest(urls.registeredHooks);
  },
  getHookCallLogs() {
    return makeGetRequest(urls.logs);
  },
};

export default api;
