<template>
  <div>
    <Breadcrumbs title="Industries"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title">
                <h5>Industries</h5><span>List of all industries</span>
              </div>
              <div class="card-buttons">
                <a href="#" v-if="$can('add-industry')" v-b-modal.modal-add-industry class="btn btn-primary">
                  Add Industry
                </a>
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
                    <b-form-select v-model="industries.perPage" :options="pageOptions"></b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>

              <div class="table-responsive datatable-vue">
                <b-table
                  show-empty
                  stacked="md"
                  :items="industries.data"
                  :fields="industriesTableFields"
                >
                  <template #cell(actions)="data" class="text-center">
                    <a href="#" title="Edit" v-if="$can('update-industry')" v-b-modal.modal-edit-industry @click.prevent="edit(data.item)" class="text-muted">
                      <i class="fa fa-edit"></i>
                    </a>
                    <a href="#" title="Delete" v-if="$can('delete-industry')" v-b-modal.modal-delete-industry @click.prevent="delete_id=data.item.id" class="text-muted">
                      <i class="fa fa-trash"></i>
                    </a>
                  </template>
                </b-table>
              </div>

              <b-col md="6" class="my-1">
                <b-pagination
                  v-model="industries.page"
                  :total-rows="industries.count"
                  :per-page="industries.perPage"
                  class="my-0"
                ></b-pagination>
              </b-col>


            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal id="modal-delete-industry" size="sm" centered @ok="remove"
             title="Delete Industry" ok-title="Delete" ok-variant="danger" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <p>Are you sure you want to delete this industry?</p>
        </div>
      </div>
    </b-modal>

    <b-modal id="modal-add-industry" size="sm" centered @ok="add"
             title="Add Industry" ok-title="Add" ok-variant="primary" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label for="add-industry-name">Industry Name</label>
            <input v-model="form.name" id="add-industry-name" class="form-control"
                   :class="{ 'is-invalid': form.errors.has('name') }"
                   type="text">
            <has-error :form="form" field="name"/>
          </div>
        </div>
      </div>
    </b-modal>


    <b-modal id="modal-edit-industry" size="sm" centered @ok="update"
             title="Edit Industry" ok-title="Update" ok-variant="primary" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label for="edit-industry-name">Industry Name</label>
            <input v-model="editItem.name" id="edit-industry-name" class="form-control"
                   :class="{ 'is-invalid': editItem.errors.has('name') }"
                   type="text">
            <has-error :form="editItem" field="name"/>
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
    name: "Industries",
    middleware: ['auth','permission:access-industry'],
    data() {
      return {
        industriesTableFields: [
          {key: 'name', label: 'Name', sortable: true,},
          'actions'
        ],

        form: new Form({
          name:"",
        }),

        filter: "",
        pageOptions: [5, 10, 15],
        editItem: new Form({
          id:0,
          name:"",
        }),
        delete_id:0,
        timeout:0,
      }

    },
    created(){
      this.fetchData();
    },
    computed: {
      ...mapState({
        industries: state => state.candidates.industries,
      }),
    },
    methods:{
      async remove(){
        try{
          const { data } = await axios.delete('/api/industries/'+this.delete_id);
          this.fetchData();
          this.$alert(data.message, data.status);
        }catch (e) {
          console.log(e);
          this.$error('some error occurred');
        }

      },
      edit(item){
        this.editItem.id = item.id;
        this.editItem.name = item.name;
      },
      async add(event){
        event.preventDefault();
        try {
          const { data } = await this.form.post('/api/industries');
          this.$alert(data.message, data.status);
          this.fetchData();
          this.$bvModal.hide('modal-add-industry');
          this.form.reset();
        }catch (e) {
          console.log(e);
          this.$error('Some error occured');
        }
      },

      async update(event){
        event.preventDefault();
        try {
          const { data } = await this.editItem.put('/api/industries/'+this.editItem.id);
          this.$alert(data.message, data.status);
          this.fetchData();
          this.$bvModal.hide('modal-edit-industry')
        }catch (e) {
          console.log(e);
          this.$error('Some error occured');
        }
      },
      filterData(){
        clearTimeout(this.timeout);
        this.timeout = setTimeout(()=>{
          this.fetchData();
        },100)

      },
      fetchData(){
        this.$store.dispatch('candidates/fetchIndustries', {search: this.filter});
      }
    },
    watch:{
      'filter':{
        handler(){
          this.filterData();
        }
      },
      'industries.page':{
        handler(){
          this.filterData()
        }
      },
      'industries.perPage':{
        handler(){
          this.filterData()
        }
      }
    }
  }
</script>

