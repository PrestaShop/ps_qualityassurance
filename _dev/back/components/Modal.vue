<template>
  <div>
    <transition name="modal-fade">
      <div class="modal show">
        <div class="modal-dialog" role="document" v-click-outside="close">
          <div class="modal-content"
               aria-labelledby="modalTitle"
               aria-describedby="modalDescription"
          >
            <header
              class="modal-header"
            >
              <slot name="header">
                <h5 class="modal-title" id="exampleModalLabel">{{ title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </slot>
            </header>
            <section
              class="modal-body"
            >
              <slot name="body" />
            </section>
            <footer class="modal-footer">
              <slot name="footer" v-if="!confirmation">
                <button
                  type="button"
                  class="btn btn-outline-secondary"
                  @click="close"
                  aria-label="Close modal"
                >
                  Close
                </button>
              </slot>

              <slot name="footer-confirmation" v-if="confirmation">
                <button
                  type="button"
                  class="btn btn-outline-secondary"
                  @click="close"
                  aria-label="Close modal"
                >
                  {{ confirmationCancel }}
                </button>

                <button
                  type="button"
                  class="btn btn-primary"
                  @click="confirm"
                >
                  {{ confirmationConfirm }}
                </button>
              </slot>
            </footer>
          </div>
        </div>
      </div>
    </transition>
    <div class="modal-backdrop show" />
  </div>
</template>

<script>
  export default {
    name: 'Modal',
    props: {
      confirmation: {
        type: Boolean,
        required: false,
        default: false,
      },
      confirmationCancel: {
        type: String,
        required: false,
        default: 'Cancel',
      },
      confirmationConfirm: {
        type: String,
        required: false,
        default: 'Confirm',
      },
      title: {
        type: String,
        required: false,
        default: 'Modal Title',
      },
    },
    methods: {
      close() {
        this.$emit('close');
      },
      confirm() {
        this.$emit('confirm');
      },
    },
  };
</script>
