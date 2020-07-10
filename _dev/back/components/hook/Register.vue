<template>
  <div class="row">
    <div class="col-lg-12">
      <alert type="danger" :message="message" v-if="message" />

      <div class="form">
        <div class="form-group">
          <label class="form-control-label" for="hook-name">
            Hook name
          </label>

          <vue-suggestion
            :items="items"
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

          <codemirror v-model="form.content" />
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
  import api from '@/lib/api';
  import Alert from '@/components/Alert';
  import {createNamespacedHelpers} from 'vuex';
  import {VueSuggestion} from 'vue-suggestion';
  import {codemirror} from 'vue-codemirror';
  import Suggestion from './Suggestion';

  const {mapGetters} = createNamespacedHelpers('hooks');

  export default {
    name: 'HookRegister',
    components: {
      Alert,
      VueSuggestion,
      codemirror,
    },
    data() {
      return {
        message: null,
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
        this.form.name = text;
        this.$store.dispatch('hooks/search', text);
      },
      registerHook() {
        this.message = null;
        api.registerHook(this.form).then((res) => {
          if (res.data.error) {
            this.message = res.data.error;
          } else {
            this.form.name = '';
            this.form.content = '';
            this.$store.dispatch('hooks/getAll');
          }
        });
      },
    },
  };
</script>
