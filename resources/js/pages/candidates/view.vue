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
                             :checked="candidate && candidate.shortlisted_by && candidate.shortlisted_by.findIndex(p => user.profile.id == p.id) > -1"
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
    name: "EditCandidate",
    middleware: ['auth','permission:view-candidate'],

    created(){
      const id = this.$route.params.id;
      this.$store.dispatch('candidates/fetchCandidate', { candidate_id : id});
    },
    computed:{
      ...mapState({
        user: state => state.auth.user,
        candidate: state => state.candidates.candidate,
      })
    },
    methods:{

      async toggleShortlist(){
        const attach = !this.candidate.shortlisted_by.find(t => t.id == this.user.profile.id);
        try {
          const form = new Form({
            attach
          })
          const { data } = await form.put('/api/candidates/'+this.candidate.id+'/shortlist');
          this.$alert(data.message, data.status);
          const id = this.$route.params.id;
          this.$store.dispatch('candidates/fetchCandidate', { candidate_id : id});

        }catch (e) {
          console.log(e);
          this.$error("some error occurred");
        }
      }

    },
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

