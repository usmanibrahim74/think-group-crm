<template>
  <div>
    <Breadcrumbs title="Roles" main="User Managemnet"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title">
                <h5>User Roles</h5><span>List of all user roles</span>
              </div>
              <div class="card-buttons">
                <b-button variant="primary" >Add Role</b-button>
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
                    <b-form-select v-model="perPage" :options="pageOptions"></b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>

              <div class="table-responsive datatable-vue">
                <b-table
                  show-empty
                  stacked="md"
                  :items="roles"
                  :fields="rolesTableFields"
                  :filter="filter"
                  :current-page="currentPage"
                  :per-page="perPage"
                  @filtered="onFiltered"
                >
                  <template #cell(actions)="data" class="text-center">
                    <a href="#" v-b-modal @click.prevent="setSelected(data.item)" class="text-muted">
                      <i class="fa fa-edit"></i>
                    </a>
                    <a href="#" v-b-modal @click.prevent="setSelected(data.item)" class="text-muted">
                      <i class="fa fa-trash"></i>
                    </a>
                  </template>
                </b-table>
              </div>

              <b-col md="6" class="my-1">
                <b-pagination
                  v-model="currentPage"
                  :total-rows="totalRows"
                  :per-page="perPage"
                  class="my-0"
                ></b-pagination>
              </b-col>


            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import { mapState } from 'vuex';

  export default {
    name: "roles",
    middleware: 'auth',
    data() {
      return {
        rolesTableFields: [

          {key: 'display_name', label: 'Display Name', sortable: true,},
          {key: 'name', label: 'Name', sortable: true,},
          {key: 'description', label: 'Description', sortable: true,},
          'actions'
        ],

        filter: null,
        currentPage: 1,
        totalRows:0,
        perPage: 10,
        pageOptions: [5, 10, 15],
      }

    },
    created(){
      this.$store.dispatch('management/fetchRoles');
    },
    mounted() {
      this.totalRows = this.roles.length
    },
    computed: {
      ...mapState({
        roles: state => state.management.roles,
      }),
    },
    methods:{
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
      },
    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
