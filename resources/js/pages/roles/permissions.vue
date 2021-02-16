<template>
  <div>
    <Breadcrumbs :title="role && role.display_name + ' Permissions'" main="Roles"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title">
                <h5>Role Permissions</h5><span>Set permissions for the role {{ role && role.display_name }}</span>
              </div>
            </div>
            <div class="card-body">
              <b-row>
                <b-col md="6">

                  <b-input-group class="datatable-btn">
                    <b-form-input v-model="filter" placeholder="Type to Search"></b-form-input>
                    <b-input-group-append>
                      <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                    </b-input-group-append>
                  </b-input-group>

                </b-col>

                <b-col md="6">
                  <b-form-group label-cols="2" label="Per page" class="mb-0 datatable-select">
                    <b-form-select v-model="permissions.perPage" :options="pageOptions"></b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>

              <div class="table-responsive datatable-vue">
                <b-table
                  show-empty
                  stacked="md"
                  :items="permissions.data"
                  :fields="permissionsTableFields"
                >
                  <template #cell(attach-role)="data" class="text-center">
                    <div class="media">
                      <div class="media-body text-center">
                        <label class="switch mb-0">
                          <input type="checkbox"
                                 :checked="role && role.permissions.findIndex(p => data.item.id == p.id) > -1"
                                 @input="syncPermission(data.item)"><span
                          class="switch-state shadow-showcase shadow-sm border"></span>
                        </label>
                      </div>
                    </div>
                  </template>
                </b-table>
              </div>

              <b-col md="6" class="my-1">
                <b-pagination
                  v-model="permissions.page"
                  :total-rows="permissions.count"
                  :per-page="permissions.perPage"
                  class="my-0"
                ></b-pagination>
              </b-col>


            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal id="modal-delete-permission" size="sm" centered @ok="remove"
             title="Delete Role" ok-title="Delete" ok-variant="danger" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <p>Are you sure you want to delete this permission?</p>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
  import { mapState } from 'vuex';
  import axios from "axios";
  import Form from 'vform';

  export default {
    name: "ViewPermissions",
    middleware: ['auth','permission:manage-role-permissions'],
    data() {
      return {
        permissionsTableFields: [

          {key: 'display_name', label: 'Display Name', sortable: true,},
          {key: 'name', label: 'Name', sortable: true,},
          {key: 'description', label: 'Description', sortable: true,},
          {key: 'attach-role', label:'Attach Role'}
        ],

        filter: "",
        pageOptions: [5, 10, 15],
        delete_id:0,
        timeout:0
      }

    },
    created(){
      const id = this.$route.params.id;
      this.$store.dispatch('management/fetchRole',{ role_id : id});
      this.$store.dispatch('management/fetchPermissions', {search: this.filter});
    },
    computed: {
      ...mapState({
        role: state => state.management.role,
        permissions: state => state.management.permissions,
      }),
    },
    methods:{
      async remove(){
        try{
          const { data } = await axios.delete('/api/permissions/'+this.delete_id);
          this.$store.dispatch('management/fetchPermissions', {search: this.filter});
          this.$alert(data.message, data.status);
        }catch (e) {
          console.log(e);
          this.$error('some error occurred');
        }

      },
      filterData(){
        clearTimeout(this.timeout);
        this.timeout = setTimeout(()=>{
          this.$store.dispatch('management/fetchPermissions', {search: this.filter});
        },100)

      },
      async syncPermission(permission){
        const index = this.role.permissions.findIndex( p => p.id == permission.id )
        if(index < 0){
          this.role.permissions.push(permission);
        }else{
          this.role.permissions.splice(index, 1);
        }
        try{
          const form = new Form({
            ...this.role
          })
          const { data } = await form.put('/api/roles/'+this.role.id);
          this.$alert(data.message, data.status);
        }catch (e) {
          console.log(e)
          this.$error('some error occured');
        }
      }
    },
    watch:{
      'filter':{
        handler(){
          this.filterData();
        }
      },
      'permissions.page':{
        handler(){
          this.filterData()
        }
      }
    }
  }
</script>

