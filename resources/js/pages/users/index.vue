<template>
  <div>
    <Breadcrumbs title="Users"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title">
                <h5>Users</h5><span>List of all users</span>
              </div>
              <div class="card-buttons">
                <router-link :to="{ name: 'users.add'}" class="btn btn-primary">Add User</router-link>
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
                    <b-form-select v-model="users.perPage" :options="pageOptions"></b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>

              <div class="table-responsive datatable-vue">
                <b-table
                  show-empty
                  stacked="md"
                  :items="users.data"
                  :fields="tableFields"

                >
                  <template #cell(actions)="data" class="text-center">
                    <router-link title="Edit" v-if="$can('update-user')" :to="{ name: 'users.edit', params: { id : data.item.id }}" class="text-muted">
                      <i class="fa fa-edit"></i>
                    </router-link>
                    <a href="#" title="Delete"  v-if="$can('delete-user')" v-b-modal.modal-delete-user @click.prevent="delete_id=data.item.id" class="text-muted">
                      <i class="fa fa-trash"></i>
                    </a>
                  </template>
                </b-table>
              </div>

              <b-col md="6" class="my-1">
                <b-pagination
                  v-model="users.page"
                  :total-rows="users.count"
                  :per-page="users.perPage"
                  class="my-0"
                ></b-pagination>
              </b-col>


            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal id="modal-delete-user" size="sm" centered @ok="remove"
             title="Delete User" ok-title="Delete" ok-variant="danger" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <p>Are you sure you want to delete this user?</p>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
  import { mapState } from 'vuex';
  import axios from "axios";

  export default {
    name: "ViewUsers",
    middleware: ['auth','permission:view-user'],
    data() {
      return {
        tableFields: [

          {key: 'name', label: 'Name', sortable: true,},
          {key: 'email', label: 'Email', sortable: true,},
          'actions'
        ],

        filter: "",
        pageOptions: [5, 10, 15],
        delete_id:0,
        timeout:0
      }

    },
    created(){
      this.$store.dispatch('management/fetchUsers',{search:this.filter});
    },
    computed: {
      ...mapState({
        users: state => state.management.users,
      }),
    },
    methods:{
      async remove(){
        try{
          const { data } = await axios.delete('/api/roles/'+this.delete_id);
          this.$store.dispatch('management/fetchRoles', {search: this.filter});
          this.$alert(data.message, data.status);
        }catch (e) {
          console.log(e);
          this.$error('some error occurred');
        }

      },
      filterData(){
        clearTimeout(this.timeout);
        this.timeout = setTimeout(()=>{
          this.$store.dispatch('management/fetchRoles', {search: this.filter});
        },100)

      }
    },
    watch:{
      'filter':{
        handler(){
          this.filterData();
        }
      },
      'roles.page':{
        handler(){
          this.filterData()
        }
      },
      'roles.perPage':{
        handler(){
          this.filterData()
        }
      }
    }
  }
</script>

