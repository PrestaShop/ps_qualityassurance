<!--**
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
     *-->
<template>
  <div id="ps-quality-assurance">
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item">
        <router-link
          to="/register"
          class="nav-link"
          active-class="active"
        >
          Register hook
        </router-link>
      </li>

      <li class="nav-item">
        <router-link
          to="/view"
          class="nav-link"
          active-class="active"
        >
          View registered hooks
        </router-link>
      </li>
    </ul>

    <router-view />
  </div>
</template>

<script>
  export default {
    name: 'App',
    mounted() {
      this.$store.dispatch('hooks/getAll');
    },
  };
</script>
