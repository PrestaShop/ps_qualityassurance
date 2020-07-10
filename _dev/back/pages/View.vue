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
                @click.prevent="viewHook(hook.id)"
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
  </div>
</template>

<script>
  import api from '@/lib/api';
  import Alert from '@/components/Alert';

  export default {
    name: 'View',
    components: {
      Alert,
    },
    data() {
      return {
        hooks: [],
        message: null,
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
    },
  };
</script>
