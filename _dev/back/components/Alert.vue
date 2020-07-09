<template>
  <div :class="`alert alert-${type}`" role="alert">
    <p class="alert-text" v-html="message" />
  </div>
</template>

<script>
  export default {
    name: 'Alert',
    props: {
      type: {
        type: String,
        required: false,
        default: 'info',
        validator: value => ['info', 'success', 'warning', 'danger'].indexOf(value) !== -1,
      },
      message: {
        type: String,
        required: true,
      },
    },
  };
</script>
