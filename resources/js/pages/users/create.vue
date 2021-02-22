<template>
  <div>
    <Breadcrumbs title="Add User" main="Users"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Add User</h5><span>Create a new user</span>
            </div>
            <div class="card-body">
              <b-form class="needs-validation" @submit.prevent="addUser">
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
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Password:</label>
                      <div class="col-sm-9">
                        <input v-model="form.password" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('password') }"
                               type="password">
                        <has-error :form="form" field="password"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Confirm Password:</label>
                      <div class="col-sm-9">
                        <input v-model="form.password_confirmation" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('password_confirmation') }"
                               type="password">
                        <has-error :form="form" field="password_confirmation"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 text-right">
                    <v-button :loading="form.busy" >Add User</v-button>
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
  import {mapState} from "vuex";

  export default {
    name: "CreateUser",
    middleware: ['auth','permission:add-user'],
    data() {
      return {
        form: new Form({
          role_id:"",
          name:"",
          emai:"",
          password:"",
          password_confirmation:[],
        })
      }

    },
    created(){
      this.$store.dispatch('management/fetchAllRoles',{search:this.filter});
    },
    computed: {
      ...mapState({
        roles: state => state.management.all_roles,
      }),
      getRoles(){
        return this.roles.filter( role => role.name != 'employer')
      }
    },
    methods:{

      async addUser(){
        try{
          const { data } = await this.form.post('/api/users/add');
          this.$alert(data.message, data.status);
          this.$router.push('/users');
        }catch (e) {
          console.log(e);
        }


      },

    }
  }
</script>

