<template>
  <div>
    <Breadcrumbs title="Add Role" main="Roles"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Add Role</h5><span>Create a new role</span>
            </div>
            <div class="card-body">
              <b-form class="needs-validation" @submit.prevent="addRole">
                <div class="form-row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Display Name</label>
                      <input v-model="form.display_name" @keyup="generateSlug" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('display_name') }"
                              type="text">
                      <has-error :form="form" field="display_name"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Name</label>
                      <input v-model="form.name" class="form-control" placeholder="must be unique, lowercase and no spaced"
                             :class="{ 'is-invalid': form.errors.has('name') }"
                              type="text">
                      <has-error :form="form" field="name"/>
                    </div>
                  </div>

                  <div class="col-12">

                    <div class="form-group">
                      <label class="col-form-label">Description</label>
                      <textarea v-model="form.description" class="form-control"
                                :class="{ 'is-invalid': form.errors.has('description') }"
                                required=""></textarea>
                      <has-error :form="form" field="description"/>
                    </div>

                  </div>
                  <div class="col-12 text-right">
                    <v-button :loading="form.busy" >Add Role</v-button>
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

  export default {
    name: "CreateRole",
    middleware: ['auth','permission:add-role'],
    data() {
      return {
        form: new Form({
          name:"",
          display_name:"",
          description:"",
          permissions:[],
        })
      }

    },
    methods:{

      generateSlug(){
        this.form.name = this.form.display_name.toLowerCase().split(' ').join('-')
      },
      async addRole(){
        try{
          console.log('add role')
          const { data } = await this.form.post('/api/roles/add');
          this.$alert(data.message, data.status);
          this.$router.push('/roles');
        }catch (e) {
          console.log(e);
        }


      },

    }
  }
</script>

