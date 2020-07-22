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
  <div class="panel">
    <alert type="danger" :message="message" v-if="message" />

    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th class="text-center">Action</th>
          <th class="text-center">Status</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="hook in hooks" :key="hook.name">
          <th scope="row">{{ hook.id }}</th>
          <td>{{ hook.name }}</td>
          <td>
            <div class="btn-group">
              <button
                type="button"
                class="btn btn-primary btn-sm"
                @click.prevent.stop="viewHook(hook)"
              >
                View
              </button>
              <button
                type="button"
                class="btn btn-danger btn-sm"
                @click.prevent="deleteHook(hook.id)"
              >
                Delete
              </button>
            </div>
          </td>
          <td class="text-center">
            <button
              type="button"
              class="btn btn-link"
              :class="{'text-danger': !isEnabled(hook), 'text-success': isEnabled(hook)}"
              @click.prevent="toogleHookStatus(hook.id)"
            >
              <i
                class="material-icons"
              >
                <template v-if="isEnabled(hook)">
                  check
                </template>
                <template v-else>
                  clear
                </template>
              </i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <modal
      v-if="isModalVisible"
      @close="closeModal"
      @confirm="confirmUpdate"
      :title="selectedHook.name"
      confirmation
    >
      <template slot="body">
        <div class="row">
          <div class="col-lg-12">
            <div class="form">
              <div class="form-group">
                <label class="form-control-label" for="hook-content">
                  Content
                </label>

                <codemirror v-model="selectedHook.content" />
              </div>
            </div>
          </div>
        </div>
      </template>
    </modal>
  </div>
</template>

<script>
  import api from '@/lib/api';
  import Alert from '@/components/Alert';
  import Modal from '@/components/Modal';
  import {codemirror} from 'vue-codemirror';

  export default {
    name: 'View',
    components: {
      Alert,
      Modal,
      codemirror,
    },
    data() {
      return {
        hooks: [],
        message: null,
        isModalVisible: false,
        selectedHook: {},
      };
    },
    mounted() {
      this.refreshHooks();
    },
    methods: {
      refreshHooks() {
        api.getRegisteredHooks().then((res) => {
          this.hooks = res.data;
        });
      },
      deleteHook(hookId) {
        this.message = null;
        api.deleteHook(hookId).then((res) => {
          if (res.data.error) {
            this.message = res.data.error;
          } else {
            this.refreshHooks();
          }
        });
      },
      confirmUpdate() {
        api.updateHook(
          {
            hookId: this.selectedHook.id,
            content: this.selectedHook.content,
          },
        ).then(() => {
          this.closeModal();
        });
      },
      toogleHookStatus(hookId) {
        api.toogleHookStatus(hookId).then(() => {
          this.refreshHooks();
        });
      },
      closeModal() {
        this.isModalVisible = false;
      },
      viewHook(hook) {
        this.selectedHook = hook;
        this.isModalVisible = true;
      },
      isEnabled(hook) {
        const enabled = parseInt(hook.enabled, 10);
        return enabled === 1;
      },
    },
  };
</script>
