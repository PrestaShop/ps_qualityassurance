<template>
  <div class="list-group-item list-group-item-action" style="z-index:9999">
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
