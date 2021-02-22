<template>
  <div>
    <Breadcrumbs title="Edit User" main="Users"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Edit User</h5><span>Edit this user</span>
            </div>
            <div class="card-body">
              <b-form class="needs-validation" @submit.prevent="updateUser">
                <div class="form-row">
                  <div class="col">
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Select Role:</label>
                      <div class="col-sm-9">
                        <select class="form-control" v-model="form.role_id" :class="{ 'is-invalid': form.errors.has('role_id') }">
                          <option value="">Select Role</option>
                          <option :value="role.id" v-for="(role,i) in getRoles" :key="i">{{ role.display_name }}</option>
                        </select>
                        <has-error :form="form" field="role_id"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Name:</label>
                      <div class="col-sm-9">
                        <input v-model="form.name" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('name') }"
                               type="text">
                        <has-error :form="form" field="name"/>
                      </div>
                    </div>


                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Email:</label>
                      <div class="col-sm-9">
                        <input v-model="form.email" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('email') }"
                               type="email">
                        <has-error :form="form" field="email"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 text-right">
                    <button type="button" class="btn btn-outline-primary" v-b-modal.modal-change-user-password >Change Password</button>
                    <v-button :loading="form.busy" >Update User</v-button>
                  </div>
                </div>
              </b-form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <b-modal id="modal-change-user-password" centered @ok="updatePassword"
             title="Change password" ok-title="Change Password" ok-variant="primary" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label class="col-form-label">Password</label>
            <input v-model="changePassword.password" class="form-control"
                   :class="{ 'is-invalid': changePassword.errors.has('password') }"
                   type="password">
            <has-error :form="changePassword" field="password"/>
          </div>
          <div class="form-group">
            <label class="col-form-label">Confirm Password</label>
            <input v-model="changePassword.password_confirmation" class="form-control"
                   :class="{ 'is-invalid': changePassword.errors.has('password_confirmation') }"
                   type="password">
            <has-error :form="changePassword" field="password_confirmation"/>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
  import Form from 'vform'
  import axios from 'axios';
  import {mapState} from "vuex";

  export default {
    name: "CreateUser",
    middleware: ['auth','permission:update-user'],
    data() {
      return {
        form: new Form({
          role_id:"",
          name:"",
          emai:"",
        }),

        changePassword: new Form({
          password:"",
          password_confirmation:""
        })
      }

    },
    created(){
      this.$store.dispatch('management/fetchAllRoles',{search:this.filter});
      const id = this.$route.params.id;
      this.$store.dispatch('management/fetchUser', {user_id: id});
    },
    computed: {
      ...mapState({
        user: state => state.management.user,
        roles: state => state.management.all_roles,
      }),
      getRoles(){
        return this.roles.filter( role => role.name != 'employer')
      }
    },
    methods:{

      async updateUser(){
        try{
          const { data } = await this.form.put('/api/users/'+this.user.id);
          this.$alert(data.message, data.status);
          this.$router.push('/users');
          this.form.reset();

        }catch (e) {
          console.log(e);
        }


      },
      async updatePassword(event){
        event.preventDefault();
        try{
          const { data } = await this.changePassword.put('/api/users/'+this.user.id+"/password");
          this.$alert(data.message, data.status);
          this.$bvModal.hide('modal-change-user-password');
          this.changePassword.reset();
        }catch (e) {
          console.log(e);
        }


      },

    },
    watch:{
      "user":{
        handler(){
          this.form.role_id = this.user.roles[0].id;
          this.form.name = this.user.name;
          this.form.email = this.user.email;
        }
      }
    }
  }
</script>

