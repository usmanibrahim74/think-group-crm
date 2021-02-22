<template>
  <div>
    <Breadcrumbs title="Change Password" main="Settings"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Change Password</h5>
              <span>Change your account password</span>
            </div>
            <div class="card-body">
              <form @submit.prevent="update" @keydown="form.onKeydown($event)">
<!--                <alert-success :form="form" :message="$t('password_updated')" />-->

                <!-- Password -->
                <div class="form-group row">
                  <label class="col-md-3 col-form-label text-md-right">{{ $t('new_password') }}</label>
                  <div class="col-md-7">
                    <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password">
                    <has-error :form="form" field="password" />
                  </div>
                </div>

                <!-- Password Confirmation -->
                <div class="form-group row">
                  <label class="col-md-3 col-form-label text-md-right">{{ $t('confirm_password') }}</label>
                  <div class="col-md-7">
                    <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" class="form-control" type="password" name="password_confirmation">
                    <has-error :form="form" field="password_confirmation" />
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group row">
                  <div class="col-md-9 ml-md-auto">
                    <v-button :loading="form.busy" type="primary">
                      {{ $t('update') }}
                    </v-button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid Ends-->
  </div>
</template>

<script>
import Form from 'vform'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: "Change Password" }
  },

  data: () => ({
    form: new Form({
      password: '',
      password_confirmation: ''
    })
  }),

  methods: {
    async update () {
      await this.form.patch('/api/settings/password')
      this.$success(this.$t('password_updated'))
      this.form.reset()
    }
  }
}
</script>
