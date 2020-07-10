/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
import './index.scss';

import Vue from 'vue';
import './directives';
import VueSuggestion from 'vue-suggestion';
import VueCodemirror from 'vue-codemirror';
import 'codemirror/mode/php/php.js';
import router from './router';
import store from './store';
import App from './App';

Vue.use(VueSuggestion);
Vue.use(
  VueCodemirror,
  {
    options: {
      mode: 'text/x-php',
      theme: 'eclipse',
      lineNumbers: true,
      styleActiveLine: true,
      matchBrackets: true,
    },
  },
);

Vue.config.productionTip = process.env.NODE_ENV === 'production';

// eslint-disable-next-line no-new
new Vue({
  router,
  store,
  el: '#ps-quality-assurance',
  template: '<app />',
  components: {App},
});
