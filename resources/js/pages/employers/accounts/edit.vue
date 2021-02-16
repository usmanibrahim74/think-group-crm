<template>
  <div>
    <Breadcrumbs title="Edit Employer Account" main="Employers"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Edit Employer Account</h5><span>Edit this employer account</span>
            </div>
            <div class="card-body">
              <b-form class="needs-validation" @submit.prevent="update">
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




                  <div class="col-12 text-right">
                    <a href="#" v-b-modal.modal-change-account-password class="btn btn-info">
                      Change Password
                    </a>
                    <v-button :loading="form.busy" >Update Account</v-button>
                  </div>
                </div>
              </b-form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal id="modal-change-account-password" centered @ok="changePassword"
             title="Change Account Password" ok-title="Change Password" ok-variant="primary" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label class="col-form-label">Password</label>
            <input v-model="changePasswordForm.password" class="form-control"
                   :class="{ 'is-invalid': changePasswordForm.errors.has('password') }"
                   type="password">
            <has-error :form="changePasswordForm" field="password"/>
          </div>
          <div class="form-group">
            <label class="col-form-label">Conform Password</label>
            <input v-model="changePasswordForm.password_confirmation" class="form-control"
                   :class="{ 'is-invalid': changePasswordForm.errors.has('password_confirmation') }"
                   type="password">
            <has-error :form="changePasswordForm" field="password_confirmation"/>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
  import Form from 'vform'

  import axios from 'axios';
  import { mapState } from 'vuex';
  import Multiselect from 'vue-multiselect';

  export default {
    name: "EditEmployerAccount",
    middleware: ['auth','permission:update-employer-account'],
    data() {
      return {
        form: new Form({
          profile:null,
          name:"",
          email:"",

        }),
        changePasswordForm: new Form({
          password:"",
          password_confirmation:""
        })
      }

    },
    components:{
      Multiselect,
    },
    created(){
      this.$store.dispatch('employers/fetchAllProfiles');
      const id = this.$route.params.id;
      this.$store.dispatch('employers/fetchAccount', { account_id : id});
    },
    computed:{
      ...mapState({
        profiles: state => state.employers.all_profiles,
        account: state => state.employers.account,
      })
    },
    methods:{

      async update(){
        try{
          console.log(this.form);
          const { data } = await this.form.put('/api/employers/accounts/'+this.account.id);
          this.$alert(data.message, data.status);
          this.$router.push('/employers/accounts');
        }catch (e) {
          console.log(e);
          this.$error('some error occurred');
        }


      },

      async changePassword(event){
        event.preventDefault();
        try {
          const { data } = await this.changePasswordForm.put('/api/employers/accounts/'+this.account.id+"/change-password");
          this.$alert(data.message, data.status);
          this.$bvModal.hide('modal-change-account-password');
          this.changePasswordForm.reset();
        }catch (e) {
          console.log(e);
          this.$error('Some error occurred');
        }
      }

    },
    watch:{
      'account':{
        deep:true,
        handler(){
          this.form.profile = this.account.profile;
          this.form.name = this.account.name;
          this.form.email = this.account.email;
        }
      }
    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

