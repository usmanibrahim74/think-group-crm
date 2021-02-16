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
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="col-form-label">Title</label>
                      <input v-model="form.title" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('title') }"
                             type="text">
                      <has-error :form="form" field="title"/>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="col-form-label">First Name</label>
                      <input v-model="form.first_name" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('first_name') }"
                             type="text">
                      <has-error :form="form" field="first_name"/>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="col-form-label">Last Name</label>
                      <input v-model="form.last_name" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('last_name') }"
                             type="text">
                      <has-error :form="form" field="last_name"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Email</label>
                      <input v-model="form.email" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('email') }"
                             type="email">
                      <has-error :form="form" field="email"/>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="col-form-label">Date of birth</label>
                      <input v-model="form.date_of_birth" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('date_of_birth') }"
                             type="date">
                      <has-error :form="form" field="date_of_birth"/>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="col-form-label">Contact</label>
                      <input v-model.number="form.contact" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('contact') }"
                             type="number">
                      <has-error :form="form" field="contact"/>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="col-form-label">Address Line 1</label>
                      <input v-model="form.address_1" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('address_1') }"
                             type="text">
                      <has-error :form="form" field="address_1"/>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="col-form-label">Address Line 2</label>
                      <input v-model="form.address_2" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('address_2') }"
                             type="text">
                      <has-error :form="form" field="address_2"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">City</label>
                      <input v-model="form.city" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('city') }"
                             type="text">
                      <has-error :form="form" field="city"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Postcode</label>
                      <input v-model="form.postcode" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('postcode') }"
                             type="text">
                      <has-error :form="form" field="postcode"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Current Salary</label>
                      <input v-model="form.current_salary" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('current_salary') }"
                             type="text">
                      <has-error :form="form" field="current_salary"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-form-label">Desired Salary</label>
                      <input v-model="form.desired_salary" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('desired_salary') }"
                             type="text">
                      <has-error :form="form" field="desired_salary"/>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="col-form-label">Industries</label>
                      <multiselect :class="{ 'is-invalid': form.errors.has('industries') }"
                        v-model="form.industries" placeholder="Search industries"
                                    label="name" track-by="id" :options="industries" :multiple="true"></multiselect>
                      <has-error :form="form" field="industries"/>
                    </div>
                  </div>

                  <div class="col-12">

                    <div class="form-group">
                      <label class="col-form-label">Notes</label>
                      <textarea v-model="form.notes" class="form-control"
                                :class="{ 'is-invalid': form.errors.has('notes') }"></textarea>
                      <has-error :form="form" field="notes"/>
                    </div>

                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label class="col-form-label">Documents</label>
                      <b-form-file multiple :class="{ 'is-invalid': form.errors.has('documents') }"
                        v-model="form.documents"
                        :state="Boolean(form.documents)"
                        placeholder="Choose files"
                        drop-placeholder="Drop files here..."
                      ></b-form-file>
                      <has-error :form="form" field="documents"/>
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
          industries:[],
          documents:null
        })
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

