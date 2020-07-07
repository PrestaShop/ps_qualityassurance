<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="form">
        <div class="form-group">
          <label class="form-control-label" for="hook-name">
            Hook name
          </label>

          <input
            type="text"
            class="form-control"
            placeholder="Hook name"
            id="hook-name"
            v-model="form.name"
          >
        </div>

        <div class="form-group">
          <label class="form-control-label" for="hook-content">
            Content
          </label>

          <textarea class="form-control" id="hook-content" v-model="form.content" />
        </div>

        <button
          type="button"
          class="btn btn-primary"
          @click.prevent="registerHook()"
        >
          Register
        </button>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'HookRegister',
    data() {
      return {
        form: {
          content: '',
          name: '',
        },
      };
    },
    method: {
      registerHook() {

      },
    },
  };
</script>
