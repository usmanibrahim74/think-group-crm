<template>
  <div class="page-wrapper">
    <div class="container-fluid p-0">
      <!-- login page start-->
      <div class="authentication-main">
        <div class="row">
          <div class="col-md-12">
            <div class="auth-innerright">
              <div class="authentication-box">
                <div class="text-center"><img alt="" src="../../assets/images/endless-logo.png"></div>
                <div class="card mt-4">
                  <div class="card-body">
                    <div class="text-center">
                      <h4>LOGIN</h4>
                      <h6>Enter your email and Password </h6>
                    </div>
                    <form class="theme-form" @submit.prevent="login" @keydown="form.onKeydown($event)">
                      <div class="form-group">
                        <label class="col-form-label pt-0">Your Email</label>
                        <input v-model="form.email" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('email') }"
                               required="" type="email">
                        <has-error :form="form" field="email" />
                      </div>
                      <div class="form-group">
                        <label class="col-form-label">Password</label>
                        <input v-model="form.password" autocomplete=""
                               :class="{ 'is-invalid': form.errors.has('password') }"
                               class="form-control" required="" type="password">
                        <has-error :form="form" field="password" />
                      </div>
                      <checkbox v-model="remember" name="remember">
                        {{ $t('remember_me') }}
                      </checkbox>
                      <div class="form-group form-row mt-3 mb-0">
                        <v-button :loading="form.busy" :block="true">
                          {{ $t('login') }}
                        </v-button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- login page end-->
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import Cookies from 'js-cookie'
import LoginWithGithub from '~/components/LoginWithGithub'

export default {
  components: {
    LoginWithGithub
  },

  middleware: 'guest',
  layout: 'guest',

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.redirect()
    },

    redirect () {
      const intendedUrl = Cookies.get('intended_url')

      if (intendedUrl) {
        Cookies.remove('intended_url')
        this.$router.push({ path: intendedUrl })
      } else {
        this.$router.push({ name: 'home' })
      }
    }
  }
}
</script>
