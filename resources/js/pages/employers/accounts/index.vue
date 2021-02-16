<template>
  <div>
    <Breadcrumbs title="Employer Accounts"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title">
                <h5>Employer Accounts</h5><span>List of all employer accounts</span>
              </div>
              <div class="card-buttons">

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
                    <b-form-select v-model="accounts.perPage" :options="pageOptions"></b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>

              <div class="table-responsive datatable-vue">
                <b-table
                  show-empty
                  stacked="md"
                  :items="accounts.data"
                  :fields="tableFields"
                >
                  <template #cell(actions)="data" class="text-center">
                    <router-link title="Edit" v-if="$can('update-employer-account')" :to="{ name: 'employers.accounts.edit', params: { id : data.item.id }}" class="text-muted">
                      <i class="fa fa-edit"></i>
                    </router-link>
                    <a href="#" title="Delete" v-if="$can('delete-employer-account')" v-b-modal.modal-delete-account @click.prevent="delete_id=data.item.id" class="text-muted">
                      <i class="fa fa-trash"></i>
                    </a>
                  </template>
                </b-table>
              </div>

              <b-col md="6" class="my-1">
                <b-pagination
                  v-model="accounts.page"
                  :total-rows="accounts.count"
                  :per-page="accounts.perPage"
                  class="my-0"
                ></b-pagination>
              </b-col>


            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal id="modal-delete-account" size="sm" centered @ok="remove"
             title="Delete Account" ok-title="Delete" ok-variant="danger" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <p>Are you sure you want to delete this account?</p>
        </div>
      </div>
    </b-modal>



  </div>
</template>

<script>
  import { mapState } from 'vuex';
  import axios from "axios";
  import Form from "vform";

  export default {
    name: "ViewAccounts",
    middleware: ['auth','permission:view-employer-account'],
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
        timeout:0,
      }

    },
    created(){
      this.fetchData();
    },
    computed: {
      ...mapState({
        accounts: state => state.employers.accounts,
      }),
    },
    methods:{
      async remove(){
        try{
          const { data } = await axios.delete('/api/employers/accounts/'+this.delete_id);
          this.fetchData();
          this.$alert(data.message, data.status);
        }catch (e) {
          console.log(e);
          this.$error('some error occurred');
        }

      },
      filterData(){
        clearTimeout(this.timeout);
        this.timeout = setTimeout(()=>{
          this.fetchData();
        },300)

      },
      fetchData(){
        this.$store.dispatch('employers/fetchAccounts', {search: this.filter});
      }
    },
    watch:{
      'filter':{
        handler(){
          this.filterData();
        }
      },
      'accounts.page':{
        handler(){
          this.filterData()
        }
      },
      'accounts.perPage':{
        handler(){
          this.filterData()
        }
      }
    }
  }
</script>

