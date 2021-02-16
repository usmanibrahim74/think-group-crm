<template>
  <div>
    <Breadcrumbs title="Attach Candidates" main="Employers  /  Profiles"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title">
                <h5>Attach Candidates</h5><span>Attach Candidates to {{ profile.name }}</span>
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
                    <div class="media">
                      <div class="media-body text-center">
                        <label class="switch mb-0">
                          <input type="checkbox"
                                 :checked="profile && profile.candidates.findIndex(p => data.item.id == p.id) > -1"
                                 @input="syncCandidates(data.item)"><span
                          class="switch-state shadow-showcase shadow-sm border"></span>
                        </label>
                      </div>
                    </div>
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
    middleware: ['auth','permission:manage-employer-candidates'],
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
      }

    },
    created(){
      this.fetchData();
      const id = this.$route.params.id;
      this.$store.dispatch('employers/fetchProfile', { employer_id : id});
    },
    computed: {
      ...mapState({
        candidates: state => state.candidates.candidates,
        profile: state => state.employers.profile,
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
        this.$store.dispatch('candidates/fetchCandidates', {search: this.filter});
      },

      async syncCandidates(candidate){
        const index = this.profile.candidates.findIndex( p => p.id == candidate.id )
        if(index < 0){
          this.profile.candidates.push(candidate);
        }else{
          this.profile.candidates.splice(index, 1);
        }
        try{
          const form = new Form({
            candidates: this.profile.candidates,
          })
          const { data } = await form.put('/api/employers/profiles/'+this.profile.id+'/candidates');
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

