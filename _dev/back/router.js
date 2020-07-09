import Vue from 'vue';
import Router from 'vue-router';

import Index from '@/pages/Index';
import Register from '@/pages/Register';
import View from '@/pages/View';

Vue.use(Router);

const router = new Router({
  routes: [
    {
      path: '/',
      component: Index,
    },
    {
      path: '/register',
      component: Register,
    },
    {
      path: '/view',
      component: View,
    },
  ],
});

export default router;
