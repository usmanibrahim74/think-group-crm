<template>
  <div>
    <Breadcrumbs title="Candidates"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title">
                <h5>{{ department }} Candidates</h5><span>List of all Candidates from {{ department.toLowerCase() }} department</span>
              </div>
              <div class="card-buttons">

              </div>
            </div>
            <div class="card-body">
              <b-row>
                <b-col md="3">

                  <b-input-group class="">
                    <b-form-input v-model="filter" placeholder="Type to Search"></b-form-input>
                    <b-input-group-append>
                      <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                    </b-input-group-append>
                  </b-input-group>

                </b-col>

                <b-col md="3" offset="1">
                  <b-input-group class="">
                    <select class="form-control" v-model="radius">
                      <option :value="rad" v-for="(rad,i) in radiusOptions" :key="i">{{ rad }} Miles</option>
                    </select>
                    <b-input-group-append>
                      <b-button >Filter</b-button>
                    </b-input-group-append>
                  </b-input-group>
                </b-col>
                <b-col md="5">
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
                    <router-link title="Edit" v-if="$can('update-candidate')" :to="{ name: 'candidates.edit', params: { id : data.item.id }}" class="text-muted">
                      <i class="fa fa-edit"></i>
                    </router-link>
                    <a href="#" title="Delete" v-if="$can('delete-candidate')" v-b-modal.modal-delete-candidate @click.prevent="delete_id=data.item.id" class="text-muted">
                      <i class="fa fa-trash"></i>
                    </a>
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

    <b-modal id="modal-delete-candidate" size="sm" centered @ok="remove"
             title="Delete Candidate" ok-title="Delete" ok-variant="danger" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <p>Are you sure you want to delete this candidate?</p>
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
    name: "ViewCandidates",
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

        filter: "",
        pageOptions: [5, 10, 15],
        delete_id:0,
        timeout:0,
        radius:5,
        radiusOptions: [5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],

        department:"",
      }

    },
    created(){
      this.fetchData();
    },
    computed: {
      ...mapState({
        candidates: state => state.candidates.candidates,
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
        let department = this.$route.query.department;
        if(!department || department == ''){
          department = "healthcare";
        }
        this.department = department.charAt(0).toUpperCase() + department.slice(1);
        this.$store.dispatch('candidates/fetchCandidates', {search: this.filter, department});
      }
    },
    watch:{
      $route:{
        handler(){
          this.fetchData();
        }
      },
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

