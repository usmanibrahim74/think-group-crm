<template>
  <div>
    <Breadcrumbs title="Add Candidate" main="Candidates"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Add Candidate</h5><span>Add a new candidate</span>
            </div>
            <div class="card-body">
              <b-form class="needs-validation" @submit.prevent="add">
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

                  <div class="col-12 text-right">
                    <v-button :loading="form.busy" >Add Candidate</v-button>
                  </div>
                </div>
              </b-form>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import Form from 'vform'

  import axios from 'axios';
  import { mapState } from 'vuex';
  import Multiselect from 'vue-multiselect';

  export default {
    name: "CreateCandidate",
    middleware: ['auth','permission:add-candidate'],
    data() {
      return {
        form: new Form({
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
        ]
      }

    },
    components:{
      Multiselect,
    },
    created(){
      this.$store.dispatch('candidates/fetchAllIndustries');
    },
    computed:{
      ...mapState({
        industries: state => state.candidates.all_industries,
      })
    },
    methods:{

      async add(){
        const objectToFormData = this.objectToFormData;
        try{

          const {data} = await this.form.submit('post','/api/candidates', {
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

    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

