<template>
  <div>
    <Breadcrumbs title="Add Permission" main="Permissions"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Add Permission</h5><span>Create a new permission</span>
            </div>
            <div class="card-body">
              <b-form class="needs-validation" @submit.prevent="addPermission">
                <div class="form-row">
                  <div class="col">
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Display Name:</label>
                      <div class="col-sm-9">
                        <input v-model="form.display_name" @keyup="generateSlug" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('display_name') }"
                               type="text">
                        <has-error :form="form" field="display_name"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Name:</label>
                      <div class="col-sm-9">
                        <input v-model="form.name" class="form-control" placeholder="must be unique, lowercase and no spaced"
                               :class="{ 'is-invalid': form.errors.has('name') }"
                               type="text">
                        <has-error :form="form" field="name"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Description:</label>
                      <div class="col-sm-9">
                      <textarea v-model="form.description" class="form-control"
                                :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                        <has-error :form="form" field="description"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 text-right">
                    <v-button :loading="form.busy" >Add Permission</v-button>
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
    name: "CreatePermission",
    middleware: ['auth','permission:add-permission'],
    data() {
      return {
        form: new Form({
          name:"",
          display_name:"",
          description:"",
          roles:[],
        })
      }

    },

    methods:{

      generateSlug(){
        this.form.name = this.form.display_name.toLowerCase().split(' ').join('-')
      },
      async addPermission(){
        try{
          const { data } = await this.form.post('/api/permissions/add');
          this.$alert(data.message, data.status);
          this.$router.push('/permissions');
        }catch (e) {
          console.log(e);
        }


      },

    }
  }
</script>
