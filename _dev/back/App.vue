<template>
  <div>
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
