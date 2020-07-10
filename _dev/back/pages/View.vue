<template>
  <div class="panel">
    <alert type="danger" :message="message" v-if="message" />

    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Action</th>
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

                <textarea class="form-control" id="hook-content" v-model="selectedHook.content" />
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

  export default {
    name: 'View',
    components: {
      Alert,
      Modal,
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
      closeModal() {
        this.isModalVisible = false;
      },
      viewHook(hook) {
        this.selectedHook = hook;
        console.log(this.selectedHook.name);
        this.isModalVisible = true;
      },
    },
  };
</script>
