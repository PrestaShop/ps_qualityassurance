<template>
  <div class="panel">
    <h3>
      <i class="icon-cogs" />
      Register hook
    </h3>
    <hook-register />
  </div>
</template>

<script>
  import HookRegister from '@/components/hook/Register';

  export default {
    name: 'Register',
    components: {
      HookRegister,
    },
  };
</script>
