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
                    <a href="#" class="text-muted" v-if="$can('view-shortlist-notes')" title="View Notes" v-b-modal.modal-employer-candidate-notes @click.prevent="shortlist = data.item.shortlist[0]">
                      <i class="fa fa-comment"></i>
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
    <b-modal hide-footer v-if="$can('view-shortlist-notes')" id="modal-employer-candidate-notes"
             title="Your Notes" ok-title="Shortlist" ok-variant="primary" class="theme-modal">
      <div class="row">
        <div class="col-12">

          <div class="timeline-content" v-if="shortlist">
            <div class="social-chat mt-0">
              <div class="your-msg m-0">
                <div class="media">
                  <div class="media-body mb-1">
                    <span class="f-w-600">{{ user.name }}
                      <span>
                        {{ shortlist.shortlisted_ago }}
                        <i class="fa fa-reply font-primary"></i>
                      </span>
                    </span>
                    <p class="m-0"><strong>You wants to contact via: </strong>{{ shortlist.contact_via }}</p>
                    <p class="m-0"><strong>Your Notes: </strong>{{ shortlist.employer_notes }}</p>
                  </div>
                </div>
              </div>
              <div class="your-msg m-0" v-for="(comment, i) in shortlist.comments" :key="i">
                <div class="media">
                  <div class="media-body mb-1">
                    <span class="f-w-600">{{ comment.commented_by }}
                      <span>
                        {{ comment.commented_ago }}
                        <i class="fa fa-reply font-primary"></i>
                      </span>
                    </span>
                    <p class="m-0">{{ comment.body }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="comments-box" v-if="$can('respond-admin')">
              <div class="media">
                <!--                    <img class="img-50 img-fluid m-r-20 rounded-circle" alt="" src="../../assets/images/user/1.jpg">-->
                <form @submit.prevent="addShortlistedComment" class="media-body">
                  <div class="input-group text-box">
                    <input class="form-control input-txt-bx"
                           type="text" name="message-to-send" :class="{ 'is-invalid': shortlistedComment.errors.has('body') }"
                           placeholder="Respond" v-model="shortlistedComment.body">

                    <has-error :form="shortlistedComment" field="body"/>
                  </div>
                </form>
              </div>
            </div>
          </div>
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

        shortlist:null,


        shortlistedComment: new Form({
          body: ""
        }),
      }

    },
    created(){
      this.fetchData();
    },
    computed: {
      ...mapState({
        user: state => state.auth.user,
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
      async addShortlistedComment(){
        try {
          const { data } = await this.shortlistedComment.post('/api/candidates/shortlist/'+this.shortlist.id+'/comments');
          this.$alert(data.message, data.status);
          this.shortlistedComment.reset();
          this.fetchData();

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
      },
      'candidates':{
        deep:true,
        handler(){
          if(this.shortlist){
            let cand = this.candidates.data.find( s => s.id == this.shortlist.candidate_id );
            this.shortlist = cand.shortlist[0];
          }
        }
      }
    }
  }
</script>

<style lang="scss">
  .media-body{
    &:after,&:before{
      content: none;
    }
  }
  .social-chat .media-body{
    padding:0;
    border: none;
    margin-bottom: 20px;
  }
</style>
