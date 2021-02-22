<template>
  <div>
    <Breadcrumbs title="Shortlisted Candidates" main="Candidates"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title">
                <h5>Shortlisted Candidates</h5><span>List of all Shortlisted Candidates</span>
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
                    <b-form-select v-model="candidates.perPage" :options="pageOptions"></b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>

              <div class="table-responsive datatable-vue">
                <b-table
                  show-empty
                  stacked="md"
                  :items="candidates.data"
                  :fields="tableFields"
                >
                  <template #cell(actions)="data" class="text-center">
                    <router-link title="View" v-if="$can('view-candidate')" :to="{ name: 'candidates.view', params: { id : data.item.id }}" class="text-muted">
                      <i class="fa fa-eye"></i>
                    </router-link>
                  </template>
                </b-table>
              </div>

              <b-col md="6" class="my-1">
                <b-pagination
                  v-model="candidates.page"
                  :total-rows="candidates.count"
                  :per-page="candidates.perPage"
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
  import axios from "axios";
  import Form from "vform";

  export default {
    name: "ShortlistedCandidates",
    middleware: ['auth','permission:view-candidate'],
    data() {
      return {
        tableFields: [
          {key: 'name', label: 'Name', sortable: true,},
          {key: 'email', label: 'Email', sortable: true,},
          {key: 'city', label: 'City', sortable: true,},
          // {key: 'current_salary', label: 'Current Salary', sortable: true,},
          // {key: 'desired_salary', label: 'Desired Salary', sortable: true,},
          {key: 'notes', label: 'Notes', sortable: true,},
          'actions'
        ],

        filter:"",
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
        candidates: state => state.candidates.shortlisted,

      }),
    },
    methods:{
      async remove(){
        try{
          const { data } = await axios.delete('/api/candidates/'+this.delete_id);
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
        this.$store.dispatch('candidates/fetchShortlisted', {search: this.filter});
      }
    },
    watch:{
      'filter':{
        handler(){
          this.filterData();
        }
      },
      'candidates.page':{
        handler(){
          this.filterData()
        }
      },
      'candidates.perPage':{
        handler(){
          this.filterData()
        }
      }
    }
  }
</script>

