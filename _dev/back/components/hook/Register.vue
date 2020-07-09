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
          <vue-suggestion
            :items="items"
            v-model="form.name"
            :set-label="getLabel"
            :item-template="suggestion"
            @changed="inputChange"
            @selected="selectedItem"
          />
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
  import {createNamespacedHelpers} from 'vuex';
  import {VueSuggestion} from 'vue-suggestion';
  import Suggestion from './Suggestion';

  const {mapGetters} = createNamespacedHelpers('hooks');

  export default {
    name: 'HookRegister',
    components: {
      VueSuggestion,
    },
    data() {
      return {
        form: {
          content: '',
          name: '',
        },
        suggestion: Suggestion,
      };
    },
    computed: {
      ...mapGetters({
        items: 'data',
      }),
    },
    methods: {
      selectedItem(item) {
        this.form.name = item.name;
      },
      getLabel(item) {
        return item.name;
      },
      inputChange(text) {
        this.$store.dispatch('hooks/search', text);
      },
      registerHook() {

      },
    },
  };
</script>
