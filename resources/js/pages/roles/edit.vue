<template>
  <div>
    <Breadcrumbs title="Edit Role" main="Roles"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Edit Role</h5><span>Update this role</span>
            </div>
            <div class="card-body">
              <b-form class="needs-validation" @submit.prevent="updateRole">
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
                    <v-button :loading="form.busy" >Update Role</v-button>
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
  import Multiselect from 'vue-multiselect'
  import { mapState } from 'vuex';
  import axios from 'axios';

  export default {
    name: "EditRole",
    middleware: ['auth','permission:update-role'],
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
    components:{
      Multiselect
    },
    created(){
      const id = this.$route.params.id;
      this.$store.dispatch('management/fetchRole',{ role_id : id});
    },
    computed: {
      ...mapState({
        role: state => state.management.role,

      }),
    },
    methods:{
      generateSlug(){
        this.form.name = this.form.display_name.toLowerCase().split(' ').join('-')
      },
      async updateRole(){
        try{
          const { data } = await this.form.put('/api/roles/'+this.role.id);
          this.$alert(data.message, data.status);
          this.$router.push('/roles');
        }catch (e) {
          console.log(e)
        }
      },
    },
    watch:{
      "role":{
        deep:true,
        handler(){
          this.form.name = this.role.name;
          this.form.display_name = this.role.display_name;
          this.form.description = this.role.description;
          this.form.permissions = this.role.permissions;
        }
      }
    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
