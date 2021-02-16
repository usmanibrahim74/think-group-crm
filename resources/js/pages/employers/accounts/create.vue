<template>
  <div>
    <Breadcrumbs title="Add Employer Account" main="Employers"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Add Employer Account</h5><span>Add a new employer account</span>
            </div>
            <div class="card-body">
              <b-form class="needs-validation" @submit.prevent="add">
                <div class="form-row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Select Employer</label>
                      <multiselect :class="{ 'is-invalid': form.errors.has('profile') }"
                                   v-model="form.profile" placeholder="Select Employer"
                                   label="name" track-by="id" :options="profiles"></multiselect>
                      <has-error :form="form" field="profile"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Name</label>
                      <input v-model="form.name" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('name') }"
                             type="text">
                      <has-error :form="form" field="name"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Email</label>
                      <input v-model="form.email" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('email') }"
                             type="email">
                      <has-error :form="form" field="email"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Password</label>
                      <input v-model="form.password" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('password') }"
                             type="password">
                      <has-error :form="form" field="password"/>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Confirm Password</label>
                      <input v-model="form.password_confirmation" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('password_confirmation') }"
                             type="password">
                      <has-error :form="form" field="password_confirmation"/>
                    </div>
                  </div>




                  <div class="col-12 text-right">
                    <v-button :loading="form.busy" >Add Account</v-button>
                  </div>
                </div>
              </b-form>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import Form from 'vform'

  import axios from 'axios';
  import { mapState } from 'vuex';
  import Multiselect from 'vue-multiselect';

  export default {
    name: "CreateEmployerAccount",
    middleware: ['auth','permission:add-employer-account'],
    data() {
      return {
        form: new Form({
          profile:null,
          name:"",
          email:"",
          password:"",
          password_confirmation:"",

        })
      }

    },
    components:{
      Multiselect,
    },
    created(){
      this.$store.dispatch('employers/fetchAllProfiles');
    },
    computed:{
      ...mapState({
        profiles: state => state.employers.all_profiles,
      })
    },
    methods:{

      async add(){
        try{
          console.log(this.form);
          const { data } = await this.form.post('/api/employers/accounts');
          this.$alert(data.message, data.status);
          this.$router.push('/employers/accounts');
        }catch (e) {
          console.log(e);
          this.$error('some error occurred');
        }


      },

    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

