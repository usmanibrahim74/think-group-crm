<template>
  <div>
    <Breadcrumbs title="Edit Permission" main="Permissions"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Edit Permission</h5><span>Update this Permission</span>
            </div>
            <div class="card-body">
              <b-form class="needs-validation" @submit.prevent="updatePermission">
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
                                ></textarea>
                      <has-error :form="form" field="description"/>
                    </div>

                  </div>
                  <div class="col-12 text-right">
                    <v-button :loading="form.busy" >Update Permission</v-button>
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
  import { mapState } from 'vuex';
  import axios from 'axios';

  export default {
    name: "EditPermission",
    middleware: ['auth','permission:update-permission'],
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
    created(){
      const id = this.$route.params.id;
      this.$store.dispatch('management/fetchPermission',{ permission_id : id});
    },
    computed: {
      ...mapState({
        permission: state => state.management.permission,
      }),
    },
    methods:{
      generateSlug(){
        this.form.name = this.form.display_name.toLowerCase().split(' ').join('-')
      },
      async updatePermission(){
        try{
          const { data } = await this.form.put('/api/permissions/'+this.permission.id);
          this.$alert(data.message, data.status);
          this.$router.push('/permissions');
        }catch (e) {
          console.log(e)
        }
      },
    },
    watch:{
      "permission":{
        deep:true,
        handler(){
          this.form.name = this.permission.name;
          this.form.display_name = this.permission.display_name;
          this.form.description = this.permission.description;
          this.form.permissions = this.permission.permissions;
        }
      }
    }
  }
</script>

