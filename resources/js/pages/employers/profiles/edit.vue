<template>
  <div>
    <Breadcrumbs title="Edit Employer Profile" main="Employers"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Edit Employer Profile</h5><span>Edit this profile</span>
            </div>
            <div class="card-body">
              <b-form class="needs-validation" @submit.prevent="update">
                <div class="form-row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label class="col-form-label">Name</label>
                      <input v-model="form.name" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('name') }"
                             type="text">
                      <has-error :form="form" field="name"/>
                    </div>
                    <div class="form-group">
                      <label class="col-form-label">Display Name</label>
                      <input v-model="form.display_name" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('display_name') }"
                             type="text">
                      <has-error :form="form" field="display_name"/>
                    </div>
                    <div class="row">
                      <div class="col-md-6">

                        <div class="form-group">
                          <label class="col-form-label">Email</label>
                          <input v-model="form.email" class="form-control"
                                 :class="{ 'is-invalid': form.errors.has('email') }"
                                 type="email">
                          <has-error :form="form" field="email"/>
                        </div>
                      </div>
                      <div class="col-md-6">

                        <div class="form-group">
                          <label class="col-form-label">Phone</label>
                          <input v-model.number="form.phone" class="form-control"
                                 :class="{ 'is-invalid': form.errors.has('phone') }"
                                 type="number">
                          <has-error :form="form" field="phone"/>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group mb-0" :class="{ 'is-invalid': form.errors.has('logo') }">
                      <label class="col-form-label">Logo</label>
                      <b-form-file @input="updateImage" accept="image/jpeg, image/png" :class="{ 'is-invalid': form.errors.has('logo') }"
                                   v-model="logo" id="logo" class="d-none"
                                   :state="Boolean(form.logo)"
                                   placeholder="Choose files"
                                   drop-placeholder="Drop files here..."
                      ></b-form-file>

                    </div>
                    <div v-if="!logo" class="text-center">
                      <img :src="'/storage/'+form.logo" alt="" class="w-100">

                      <b-button-group class="mt-2">
                        <label for="logo" class="btn btn-primary m-0">
                          <i class="fa fa-camera"></i>
                        </label>
                      </b-button-group>
                    </div>
                    <div v-if="imageSrc && !cropImg">
                      <vue-cropper
                        ref='cropper'
                        :guides="true"
                        :view-mode=0
                        drag-mode="crop"
                        :aspectRatio=1.777
                        :auto-crop-area="0.7"
                        :min-container-width="250"
                        :min-container-height="100"
                        :background="true"
                        :rotatable="true"
                        :movable="true"
                        :src="imageSrc"
                        alt="Source Image"
                        :crop="cropImage"
                        :img-style="{ 'width': '200px', 'height': '150px' }">
                      </vue-cropper>
                      <div class="mt-2 text-center">
                        <b-button-group>
                          <b-button variant="outline-primary" @click="zoominImage"><i class="fa fa-search-plus"></i></b-button>
                          <b-button variant="outline-primary" @click="zoomoutImage"><i class="fa fa-search-minus"></i></b-button>
                          <b-button variant="outline-primary" @click="generateImage"><i class="fa fa-check"></i></b-button>
                        </b-button-group>
                      </div>
                    </div>
                    <div v-if="cropImg" class="text-center">
                      <img :src="cropImg" alt="" class="w-100">
                      <b-button-group class="mt-2">
                        <label for="logo" class="btn btn-primary m-0">
                          <i class="fa fa-camera"></i>
                        </label>
                        <button class="btn btn-primary" @click="recropImage">
                          <i class="fa fa-crop"></i>
                        </button>
                      </b-button-group>
                    </div>
                    <has-error :form="form" class="text-center" field="logo"/>

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


                  <div class="col-12">

                    <div class="form-group">
                      <label class="col-form-label">Notes</label>
                      <textarea v-model="form.notes" class="form-control"
                                :class="{ 'is-invalid': form.errors.has('notes') }"></textarea>
                      <has-error :form="form" field="notes"/>
                    </div>

                  </div>
                  <div class="col-12 text-right">
                    <v-button :loading="form.busy" >Update Employer</v-button>
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
  import VueCropper from 'vue-cropperjs';
  import 'cropperjs/dist/cropper.css';

  export default {
    name: "EditEmployerProfile",
    middleware: ['auth','permission:update-employer-profile'],
    data() {
      return {
        form: new Form({
          name:"",
          display_name:"",
          email:"",
          phone:"",
          address_1:"",
          address_2:"",
          city:"",
          postcode:"",
          notes:"",
          logo:null
        }),
        logo:null,
        imageSrc:"",
        cropImg:null
      }

    },
    created(){
      const id = this.$route.params.id;
      this.$store.dispatch('employers/fetchProfile', { employer_id : id});
    },
    components:{
      VueCropper
    },
    computed:{
      ...mapState({
        profile: state => state.employers.profile,
      })
    },

    methods:{

      zoominImage(){
        console.log(this.$refs);
        this.$refs.cropper.relativeZoom(0.1);
      },
      zoomoutImage(){
        this.$refs.cropper.relativeZoom(-0.1);
      },

      getNoImgUrl(){
        var images = require.context('~/assets/images/', false, /\.png$/)
        return images('./no-image.png');

      },

      generateImage(){
        this.imageData = this.$refs.cropper.getData();
        this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL();
      },

      recropImage(){
        this.cropImg = null;
      },

      cropImage(){
        // this.imageData = this.$refs.cropper.getData();
        // console.log('imageData',this.imageData);

        // this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL();
      },

      updateImage(){
        this.cropImg = null;
        if(this.logo){
          this.imageSrc = null;
          let reader = new FileReader();
          reader.onload = (e)=> {
            this.imageSrc = e.target.result;
          };
          reader.onerror = function(error) {
            alert(error);
          };
          reader.readAsDataURL(this.logo);

        }else{
          this.imageSrc = null;
        }
      },

      async update(){
        // const objectToFormData = this.objectToFormData
        this.form.logo = this.cropImg;
        try{
          const { data } =  await this.form.put('/api/employers/profiles/'+this.profile.id);
          this.$alert(data.message, data.status);
          this.$router.push('/employers/profiles');

        }catch (e) {
          console.log(e);
          // this.$error('some error occurred');
        }


      },

    },
    watch:{
      'profile':{
        deep:true,
        handler(){
          this.form.name = this.profile.name;
          this.form.display_name = this.profile.display_name;
          this.form.email = this.profile.email;
          this.form.phone = this.profile.phone;
          this.form.address_1 = this.profile.address_1;
          this.form.address_2 = this.profile.address_2;
          this.form.city = this.profile.city;
          this.form.postcode = this.profile.postcode;
          this.form.notes = this.profile.notes;
          this.form.logo = this.profile.logo;

        }
      }
    }

  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

