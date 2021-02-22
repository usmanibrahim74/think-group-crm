<template>
  <div>
    <Breadcrumbs title="View Candidate" main="Candidates"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">

              <div class="card-title">
                <h5>Employer</h5><span>Employer details</span>
              </div>

            </div>
            <div class="card-body">
              <div class="row" v-if="profile">
                <div class="col-md-6">
                  <p><strong>Name:</strong> {{ profile.name }}</p>
                  <p><strong>Email:</strong> {{ profile.email }}</p>
                  <p><strong>Phone:</strong> {{ profile.phone }}</p>
                  <p><strong>Address:</strong> {{ profile.address_1 }} {{ profile.address_2 }}</p>
                  <p><strong>City:</strong> {{ profile.city }}</p>
                  <p><strong>Postcode:</strong> {{ profile.postcode }}</p>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="row" v-if="$can('view-shortlisted-candidate')">
        <div class="col-md-12">

          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title">
                <h5>Shortlisted Candidates</h5><span>List of candidates shortlisted by employers</span>
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
                    <b-form-select v-model="shortlisted.perPage" :options="pageOptions"></b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>

              <div class="table-responsive datatable-vue">
                <b-table
                  show-empty
                  stacked="md"
                  :items="shortlisted.data"
                  :fields="tableFields"
                >
                  <template #cell(actions)="data" class="text-center">
                    <router-link title="View" v-if="$can('view-candidate')" :to="{ name: 'candidate.view', params: { id : data.item.id }}" class="text-muted">
                      <i class="fa fa-eye"></i>
                    </router-link>
                  </template>
                </b-table>
              </div>

              <b-col md="6" class="my-1">
                <b-pagination
                  v-model="shortlisted.page"
                  :total-rows="shortlisted.count"
                  :per-page="shortlisted.perPage"
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

  import Form from 'vform';
  import { mapState } from 'vuex';

  export default {
    name: "ViewEmployer",
    middleware: ['auth','permission:view-employer-profile'],
    data(){
      return {
        tableFields: [
          {key: 'name', label: 'Name', sortable: true,},
          {key: 'email', label: 'Email', sortable: true,},
          {key: 'city', label: 'City', sortable: true,},
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
      const id = this.$route.params.id;
      this.$store.dispatch('employers/fetchProfile', { employer_id : id});

    },
    mounted() {
      setTimeout(() => {
        this.fetchData();
      },1000)
    },
    computed:{
      ...mapState({
        user: state => state.auth.user,
        profile: state => state.employers.profile,
        shortlisted: state => state.employers.shortlisted
      })
    },
    methods:{


      filterData(){
        clearTimeout(this.timeout);
        this.timeout = setTimeout(()=>{
          this.fetchData();
        },500)

      },

      fetchData(){
        if(this.$can('view-shortlisted-candidate')){
          const id = this.$route.params.id;
          this.$store.dispatch('employers/fetchShortlisted', {search: this.filter,id:id});
        }
      }

    },
    watch:{
      'filter':{
        handler(){
          this.filterData();
        }
      },
      'shortlisted.page':{
        handler(){
          this.filterData()
        }
      },
      'shortlisted.perPage':{
        handler(){
          this.filterData()
        }
      }
    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

