<template>
  <div class="panel">
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
              <button type="button" class="btn btn-primary btn-sm">View</button>
              <button type="button" class="btn btn-danger btn-sm">Delete</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  import api from '@/lib/api';

  export default {
    name: 'View',
    data() {
      return {
        hooks: [],
      };
    },
    mounted() {
      api.getRegisteredHooks().then((res) => {
        this.hooks = res.data;
      });
    },
  };
</script>
