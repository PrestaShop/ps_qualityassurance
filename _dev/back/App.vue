<template>
  <div>
    <div class="nav-pills">
      <ul class="nav nav-pills" role="tablist">
        <li class="nav-item">
          <router-link
            to="/register"
            class="nav-link active"
          >
            Register hook
          </router-link>
        </li>

        <li class="nav-item">
          <router-link
            class="nav-link active"
            to="/view"
          >
            View registered hooks
          </router-link>
        </li>
      </ul>
    </div>

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
