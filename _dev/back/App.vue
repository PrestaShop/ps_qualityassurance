<template>
  <div>
    <router-link
      to="/register"
    >
      Register hook
    </router-link>
    <router-link
      to="/view"
    >
      View registered hooks
    </router-link>

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
