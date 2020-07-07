import Vue from 'vue';
import Router from 'vue-router';

import Index from '@/pages/Index';

Vue.use(Router);

const router = new Router({
  routes: [
    {
      path: '/',
      component: Index,
    },
  ],
});

export default router;
