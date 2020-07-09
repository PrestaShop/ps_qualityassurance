<template>
  <div class="list-group-item list-group-item-action">
    <b>#{{ item.id_hook }}</b>
    <span>{{ item.name }}</span>
  </div>
</template>

<script>
  export default {
    props: {
      item: {
        type: Object,
        required: true,
      },
    },
  };
</script>
