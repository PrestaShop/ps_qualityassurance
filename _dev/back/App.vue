<template>
  <div>
    <router-view />
  </div>
</template>

<script>
  export default {
    name: 'App',
    props: {
      hooks: {
        type: Array,
        required: true,
      },
    },

    mounted() {
      this.$store.dispatch('hooks/data', this.hooks);
    },
  };
</script>
