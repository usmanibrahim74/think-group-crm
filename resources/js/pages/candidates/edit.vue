<template>
  <div>
    <Breadcrumbs title="Edit Candidate" main="Candidates"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Edit Candidate</h5><span>Edit this candidate</span>
            </div>
            <div class="card-body">
              <b-form class="needs-validation" @submit.prevent="update">
                <div class="form-row">

                  <div class="col">
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Title:</label>
                      <div class="col-sm-9">
                        <select class="form-control" placeholder="Select Title" v-model="form.title" :class="{ 'is-invalid': form.errors.has('title') }">
                          <option value="">Select Title</option>
                          <option :value="title" v-for="(title,i) in titles" :key="i">{{ title }}</option>
                        </select>
                        <has-error :form="form" field="title"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">First Name:</label>
                      <div class="col-sm-9">
                        <input v-model="form.first_name" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('first_name') }"
                               type="text">
                        <has-error :form="form" field="first_name"/>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Last Name:</label>
                      <div class="col-sm-9">
                        <input v-model="form.last_name" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('last_name') }"
                               type="text">
                        <has-error :form="form" field="last_name"/>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Email</label>
                      <div class="col-sm-9">

                        <input v-model="form.email" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('email') }"
                               type="email">
                        <has-error :form="form" field="email"/>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Date of birth</label>
                      <div class="col-sm-9">

                        <input v-model="form.date_of_birth" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('date_of_birth') }"
                               type="date">
                        <has-error :form="form" field="date_of_birth"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Contact</label>
                      <div class="col-sm-9">
                        <input v-model.number="form.contact" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('contact') }"
                               type="number">
                        <has-error :form="form" field="contact"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Address Line 1</label>
                      <div class="col-sm-9">
                        <input v-model="form.address_1" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('address_1') }"
                               type="text">
                        <has-error :form="form" field="address_1"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Address Line 2</label>
                      <div class="col-sm-9">
                        <input v-model="form.address_2" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('address_2') }"
                               type="text">
                        <has-error :form="form" field="address_2"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">City</label>
                      <div class="col-sm-9">
                        <input v-model="form.city" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('city') }"
                               type="text">
                        <has-error :form="form" field="city"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Postcode</label>
                      <div class="col-sm-9">
                        <input v-model="form.postcode" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('postcode') }"
                               type="text">
                        <has-error :form="form" field="postcode"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Current Salary</label>
                      <div class="col-sm-9">
                        <input v-model="form.current_salary" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('current_salary') }"
                               type="text">
                        <has-error :form="form" field="current_salary"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Desired Salary</label>
                      <div class="col-sm-9">
                        <input v-model="form.desired_salary" class="form-control"
                               :class="{ 'is-invalid': form.errors.has('desired_salary') }"
                               type="text">
                        <has-error :form="form" field="desired_salary"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Department:</label>
                      <div class="col-sm-9">
                        <select class="form-control" placeholder="Select Department" v-model="form.department_id" :class="{ 'is-invalid': form.errors.has('department_id') }">
                          <option value="">Select Department</option>
                          <option value="1" >Healthcare</option>
                          <option value="2" >Education</option>
                        </select>
                        <has-error :form="form" field="department_id"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Industries</label>
                      <div class="col-sm-9">
                        <multiselect :class="{ 'is-invalid': form.errors.has('industries') }"
                                     v-model="form.industries" placeholder="Search industries"
                                     label="name" track-by="id" :options="industries" :multiple="true"></multiselect>
                        <has-error :form="form" field="industries"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-sm-3">Notes</label>
                      <div class="col-sm-9">
                        <textarea v-model="form.notes" class="form-control"
                                  :class="{ 'is-invalid': form.errors.has('notes') }"></textarea>
                        <has-error :form="form" field="notes"/>
                      </div>
                    </div>
                    <div class="form-group row" v-for="(document,i) in form.documents" :key="i">
                      <label class="col-form-label col-sm-3">{{ document.name }}</label>
                      <div class="col-sm-9">
                        <b-form-file :class="{ 'is-invalid': form.errors.has('documents') }"
                                     v-model="document.file"
                                     :state="Boolean(form.documents)"
                                     placeholder="Choose file"
                                     drop-placeholder="Drop file here..."
                        ></b-form-file>
                        <has-error :form="form" field="documents"/>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 mb-3" v-if="candidate && candidate.uploads.length">
                    <b-list-group tag="ul">
                      <b-list-group-item v-for="(upload,i) in candidate.uploads" :key="i" tag="li" class="d-flex justify-content-between align-items-center">
                        <span>{{ upload.name }}</span>
                        <div>
                          <a href="#" title="Delete" v-b-modal.modal-delete-upload @click.prevent="delete_id=upload.id" class="text-muted">
                            <i class="fa fa-trash"></i>
                          </a>
                          <a :href="'/storage/'+upload.path" title="Downlod" download=""  class="text-muted">
                            <i class="fa fa-download"></i>
                          </a>
                        </div>
                      </b-list-group-item>
                    </b-list-group>
                  </div>
                  <div class="col-12 text-right">
                    <v-button :loading="form.busy" >Update Candidate</v-button>
                  </div>
                </div>
              </b-form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal id="modal-delete-upload" size="sm" centered @ok="remove"
             title="Delete Document" ok-title="Delete" ok-variant="danger" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <p>Are you sure you want to delete this document?</p>
        </div>
      </div>
    </b-modal>

  </div>
</template>

<script>
  import Form from 'vform'

  import axios from 'axios';
  import { mapState } from 'vuex';
  import Multiselect from 'vue-multiselect';

  export default {
    name: "EditCandidate",
    middleware: ['auth','permission:update-candidate'],
    data() {
      return {
        form: new Form({
          _method:'PUT',
          title:"",
          first_name:"",
          last_name:"",
          date_of_birth:"",
          email:"",
          contact:"",
          address_1:"",
          address_2:"",
          city:"",
          postcode:"",
          current_salary:"",
          desired_salary:"",
          notes:"",
          department_id:"",
          industries:[],
          documents:[
            {name:'CV', file:null},
            {name:'Safe CV', file:null},
            {name:'UK Passport/Work Permit', file:null},
            {name:'Passport Photo', file:null},
            {name:'Proof of Address 1', file:null},
            {name:'Proof of Address 2', file:null},
            {name:'Reference 1', file:null},
            {name:'Reference 2', file:null},
            {name:'National Insurance', file:null},
            {name:'DBS Certificate', file:null},
            {name:'Training Certificate 1', file:null},
            {name:'Training Certificate 2', file:null},
            {name:'Training Certificate 3', file:null},
            {name:'Healthcare NMC', file:null},
          ]
        }),
        titles:[
          'Mr',
          'Mrs',
          'Miss',
          'Ms',
          'Dr'
        ],
        delete_id: 0,

      }

    },
    components:{
      Multiselect,
    },
    created(){
      this.$store.dispatch('candidates/fetchAllIndustries');
      const id = this.$route.params.id;
      this.$store.dispatch('candidates/fetchCandidate', { candidate_id : id});
    },
    computed:{
      ...mapState({
        industries: state => state.candidates.all_industries,
        candidate: state => state.candidates.candidate,
      })
    },
    methods:{

      async update(){
        const objectToFormData = this.objectToFormData;
        try{

          const { data } = await this.form.post('/api/candidates/'+this.candidate.id, {
            transformRequest: [ (data, headers) => {
              return objectToFormData(data)
            }],
          });
          this.$alert(data.message, data.status);
          this.$router.push('/candidates');
        }catch (e) {
          console.log(e);
          this.$error('some error occurred');
        }


      },

      async remove(){
        try{
          const index = this.candidate.uploads.findIndex( upload => upload.id == this.delete_id );
          const { data } = await axios.delete('/api/uploads/'+this.delete_id);
          this.candidate.uploads.splice(index, 1);
          this.$alert(data.message, data.status);
        }catch (e) {
          console.log(e);
          this.$error('some error occurred');
        }

      },

    },
    watch:{
      'candidate':{
        deep:true,
        handler(){
          this.form.title = this.candidate.title;
          this.form.first_name = this.candidate.first_name;
          this.form.last_name = this.candidate.last_name;
          this.form.date_of_birth = this.candidate.date_of_birth;
          this.form.email = this.candidate.email;
          this.form.contact = this.candidate.contact;
          this.form.address_1 = this.candidate.address_1;
          this.form.address_2 = this.candidate.address_2;
          this.form.city = this.candidate.city;
          this.form.postcode = this.candidate.postcode;
          this.form.current_salary = this.candidate.current_salary;
          this.form.desired_salary = this.candidate.desired_salary;
          this.form.notes = this.candidate.notes;
          this.form.department_id = this.candidate.department_id;
          this.form.industries = this.candidate.industries;
        }
      }
    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

