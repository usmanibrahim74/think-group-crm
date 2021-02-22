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
                <h5>Candidate</h5><span>Candidate details</span>
              </div>
              <div class="card-actions" v-if="$can('shortlist-candidate')">
                <div class="media">
                  <div class="media-body text-center d-flex align-items-center">
                    <label class="switch mb-0">
                      <input type="checkbox" id="shortlist"
                             v-model="isShortlisted"
                             @input="toggleShortlist"><span
                      class="switch-state shadow-showcase shadow-sm border"></span>
                    </label>
                    <label for="shortlist" class="mb-0 ml-2"><strong>Shortlist</strong></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row" v-if="candidate">
                <div class="col-md-6">
                  <p><strong>Name:</strong> {{ candidate.name }}</p>
                  <p><strong>Email:</strong> {{ candidate.email }}</p>
                  <p><strong>Date of Birth:</strong> {{ candidate.date_of_birth }}</p>
                  <p><strong>Contact:</strong> {{ candidate.contact }}</p>
                  <p><strong>Address:</strong> {{ candidate.address_1 }} {{ candidate.address_2 }}</p>
                  <p><strong>City:</strong> {{ candidate.city }}</p>
                  <p><strong>Postcode:</strong> {{ candidate.postcode }}</p>
                  <p><strong>Industries:</strong> <span class="badge badge-info" v-for="(industry,i) in candidate.industries" :key="i">{{ industry.name }} </span></p>
                </div>
                <div class="col-md-6">
                  <p><strong>Documents:</strong></p>
                  <b-list-group tag="ul">
                    <b-list-group-item v-for="(upload,i) in candidate.uploads" :key="i" tag="li" class="d-flex justify-content-between align-items-center">
                      <span>{{ upload.name }}</span>
                      <div>
                        <a :href="'/storage/'+upload.path" title="Downlod" download=""  class="text-muted">
                          <i class="fa fa-download"></i>
                        </a>
                      </div>
                    </b-list-group-item>
                  </b-list-group>
                </div>
              </div>
              <div class="row" v-if="$can('view-comment') || $can('add-comment')">
                <div class="col">
                  <h5 class="font-weight-bold mt-4">
                    Comments
                  </h5>
                  <hr>
                  <div class="timeline-content" v-if="candidate">
                    <div class="social-chat" v-if="$can('view-comment')">
                      <div class="your-msg m-0" v-for="(comment,i) in candidate.comments" :key="i">
                        <div class="media">
                          <!--                      <img class="img-50 img-fluid m-r-20 rounded-circle" alt="" src="../../assets/images/user/1.jpg">-->
                          <div class="media-body">
                            <span class="f-w-600">{{ comment.commented_by }} <span>{{ comment.commented_ago }}<i class="fa fa-reply font-primary"></i></span></span>
                            <p>{{ comment.body }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="comments-box" v-if="$can('add-comment')">
                      <div class="media">
                        <!--                    <img class="img-50 img-fluid m-r-20 rounded-circle" alt="" src="../../assets/images/user/1.jpg">-->
                        <form @submit.prevent="addComment" class="media-body">
                          <div class="input-group text-box">
                            <input class="form-control input-txt-bx"
                                   type="text" name="message-to-send" :class="{ 'is-invalid': comment.errors.has('body') }"
                                   placeholder="Post Your commnets" v-model="comment.body">
                            <!--                        <div class="input-group-append">-->
                            <!--                          <button class="btn btn-transparent" type="button"><i class="fa fa-smile-o">  </i></button>-->
                            <!--                        </div>-->

                            <has-error :form="comment" field="body"/>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="row" v-if="$can('view-shortlisted-by')">
        <div class="col-md-12">

          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title">
                <h5>Shortlisted By</h5><span>List of employers who shortlisted this candidate</span>
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
                    <b-form-select v-model="shortlistedBy.perPage" :options="pageOptions"></b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>

              <div class="table-responsive datatable-vue">
                <b-table
                  show-empty
                  stacked="md"
                  :items="shortlistedBy.data"
                  :fields="tableFields"
                >
                  <template #cell(actions)="data" class="text-center">
                    <router-link title="View" v-if="$can('view-employer-profile')"
                                 :to="{ name: 'employers.profiles.view', params: { id : data.item.id }}" class="text-muted">
                      <i class="fa fa-eye"></i>
                    </router-link>
                    <router-link title="Edit" v-if="$can('update-employer-profile')" :to="{ name: 'employers.profiles.edit', params: { id : data.item.id }}" class="text-muted">
                      <i class="fa fa-edit"></i>
                    </router-link>
                    <router-link title="Attach Candidates" v-if="$can('manage-employer-candidates')" :to="{ name: 'employers.profiles.candidates', params: { id : data.item.id }}" class="text-muted">
                      <i class="fa fa-users"></i>
                    </router-link>
                    <a href="#" class="text-muted" title="View Employer Notes" v-b-modal.modal-employer-candidate-notes @click.prevent="shortlistedByEmployer = data.item">
                      <i class="fa fa-comment"></i>
                    </a>
                  </template>
                </b-table>
              </div>

              <b-col md="6" class="my-1">
                <b-pagination
                  v-model="shortlistedBy.page"
                  :total-rows="shortlistedBy.count"
                  :per-page="shortlistedBy.perPage"
                  class="my-0"
                ></b-pagination>
              </b-col>


            </div>
          </div>

        </div>
      </div>
    </div>

    <b-modal v-if="$can('shortlist-candidate')" id="modal-shortlist-candidate" centered @ok="shortlistCandidate" @hidden="isShortlisted = false"
             title="Shortlist Candidate" ok-title="Shortlist" ok-variant="primary" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label class="col-form-label">Contact Via</label>
            <select class="form-control" v-model="shortlist.contact_via"
                    :class="{ 'is-invalid': shortlist.errors.has('contact_via') }">
              <option value="">Select</option>
              <option :value="contactVia" v-for="(contactVia,i) in contactViaOptions" :key="i">{{ contactVia }}</option>
            </select>
            <has-error :form="shortlist" field="contact_via"/>
          </div>
          <div class="form-group">
            <label class="col-form-label">Notes</label>
            <textarea v-model="shortlist.employer_notes" class="form-control"
                      :class="{ 'is-invalid': shortlist.errors.has('employer_notes') }"></textarea>
            <has-error :form="shortlist" field="employer_notes"/>
          </div>
        </div>
      </div>
    </b-modal>

    <b-modal hide-footer v-if="$can('view-employer-notes-for-shortlisted-candidate') || true" id="modal-employer-candidate-notes" centered @ok="shortlistCandidate" @hidden="isShortlisted = false"
             title="Employer Notes" ok-title="Shortlist" ok-variant="primary" class="theme-modal">
      <div class="row">
        <div class="col-12">

          <div class="timeline-content" v-if="shortlistedByEmployer">
            <div class="social-chat mt-0">
              <div class="your-msg m-0">
                <div class="media">
                  <div class="media-body">
                    <span class="f-w-600">{{ shortlistedByEmployer.name }}
                      <span>
                        {{ shortlistedByEmployer.shortlist[0].shortlisted_ago }}
                        <i class="fa fa-reply font-primary"></i>
                      </span>
                    </span>
                    <p class="m-0"><strong>Employer wants to contact via: </strong>{{ shortlistedByEmployer.shortlist[0].contact_via }}</p>
                    <p class="m-0"><strong>Employer Notes: </strong>{{ shortlistedByEmployer.shortlist[0].employer_notes }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="comments-box" v-if="$can('add-comment')">
              <div class="media">
                <!--                    <img class="img-50 img-fluid m-r-20 rounded-circle" alt="" src="../../assets/images/user/1.jpg">-->
                <form @submit.prevent="addComment" class="media-body">
                  <div class="input-group text-box">
                    <input class="form-control input-txt-bx"
                           type="text" name="message-to-send" :class="{ 'is-invalid': comment.errors.has('body') }"
                           placeholder="Inform Employer" v-model="comment.body">
                    <!--                        <div class="input-group-append">-->
                    <!--                          <button class="btn btn-transparent" type="button"><i class="fa fa-smile-o">  </i></button>-->
                    <!--                        </div>-->

                    <has-error :form="comment" field="body"/>
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

  import Form from 'vform';
  import { mapState } from 'vuex';

  export default {
    name: "EditCandidate",
    middleware: ['auth','permission:view-candidate'],
    data(){
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
        comment: new Form({
          body: ""
        }),
        filter: "",
        pageOptions: [5, 10, 15],
        delete_id:0,
        timeout:0,
        shortlist: new Form({
          attach:true,
          contact_via: "",
          employer_notes:""
        }),
        isShortlisted: false,
        contactViaOptions:[
          'email',
          'phone',
        ],

        shortlistedByEmployer:null
      }
    },
    created(){
      const id = this.$route.params.id;
      this.$store.dispatch('candidates/fetchCandidate', { candidate_id : id});

    },
    mounted() {
      setTimeout(() => {
        this.fetchData();
      },1000)
    },
    computed:{
      ...mapState({
        user: state => state.auth.user,
        candidate: state => state.candidates.candidate,
        shortlistedBy: state => state.candidates.shortlistedBy
      }),

    },
    methods:{

      async toggleShortlist(){
        const attach = !this.candidate.shortlisted_by.find(t => t.id == this.user.id);
        try {
          if(!attach){
            const form = new Form({
              attach
            })
            const { data } = await form.put('/api/candidates/'+this.candidate.id+'/shortlist');
            this.$alert(data.message, data.status);
            this.refreshCandidate();
          }else{
            this.$bvModal.show('modal-shortlist-candidate');
          }


        }catch (e) {
          console.log(e);
          this.$error("some error occurred");
        }
      },

      refreshCandidate(){
        const id = this.$route.params.id;
        this.$store.dispatch('candidates/fetchCandidate', { candidate_id : id});
      },

      async shortlistCandidate(event){
        event.preventDefault();
        const { data } = await this.shortlist.put('/api/candidates/'+this.candidate.id+'/shortlist');
        this.$alert(data.message, data.status);
        this.refreshCandidate();
        this.$bvModal.hide('modal-shortlist-candidate')
      },

      filterData(){
        clearTimeout(this.timeout);
        this.timeout = setTimeout(()=>{
          this.fetchData();
        },500)

      },

      async addComment(){
        try {
          const { data } = await this.comment.post('/api/candidates/'+this.candidate.id+'/comments')
          this.$alert(data.message, data.status);
          const id = this.$route.params.id;
          this.$store.dispatch('candidates/fetchCandidate', { candidate_id : id});
          this.comment.reset();
        }catch (e) {
          console.log(e);
          this.$error('some error occurred');
        }
      },

      fetchData(){
        if(this.$can('view-shortlisted-by')){
          const id = this.$route.params.id;
          this.$store.dispatch('candidates/fetchShortlistedBy', {search: this.filter,id:id});
        }
      }

    },
    watch:{
      'candidate':{
        deep:true,
        handler(){
          if(this.$can('shortlist-candidate')){
            this.isShortlisted = this.user && this.candidate && this.candidate.shortlisted_by.find(t => t.id == this.user.id);
          }
        }
      },
      'filter':{
        handler(){
          this.filterData();
        }
      },
      'shortlistedBy.page':{
        handler(){
          this.filterData()
        }
      },
      'shortlistedBy.perPage':{
        handler(){
          this.filterData()
        }
      }
    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
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

