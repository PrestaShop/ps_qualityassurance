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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *-->
<template>
  <div class="panel">
    <table class="table table-striped" v-for="(logs, requestIdentifier) in logsGroupedByRequest" :key="requestIdentifier">
      <thead>
      <tr>
        <th>#</th>
        <th>Hook</th>
        <th>Parameters</th>
        <th>Output</th>
        <th>Time</th>
        <th>Status</th>
      </tr>
      </thead>
      <tbody>
        <tr v-for="log in logs" :key="log.id" v-bind:class="{ 'table-danger': (log.error === '1') }">
          <th scope="row">{{ log.id }}</th>
          <td>{{ log.hook_name }}</td>
          <td>{{ log.hook_parameters }}</td>
          <td>{{ log.output }}</td>
          <td>{{ log.called_at }}</td>
          <td>
            <i
              class="material-icons"
            >
              <template v-if="log.error === '0'">
                check
              </template>
              <template v-else>
                clear
              </template>
            </i>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  import api from '@/lib/api';

  export default {
    name: 'Logs',
    data() {
      return {
        logsGroupedByRequest: [],
      };
    },
    mounted() {
      this.refreshLogs();
    },
    methods: {
      refreshLogs() {
        api.getHookCallLogs().then((res) => {
          this.logsGroupedByRequest = res.data;
        });
      },
    },
  };
</script>
