<template>
  <div>
    <Breadcrumbs title="Roles" main="User Managemnet"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div class="card-title">
                <h5>User Roles</h5><span>List of all user roles</span>
              </div>
              <div class="card-buttons">
                <b-button variant="primary" v-b-modal.modal-add-role >Add Role</b-button>
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
                    <b-form-select v-model="perPage" :options="pageOptions"></b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>

              <div class="table-responsive datatable-vue">
                <b-table
                  show-empty
                  stacked="md"
                  :items="roles"
                  :fields="rolesTableFields"
                  :filter="filter"
                  :current-page="currentPage"
                  :per-page="perPage"
                  @filtered="onFiltered"
                >
                  <template #cell(actions)="data" class="text-center">
                    <a href="#" v-b-modal.modal-edit-role @click.prevent="setSelected(data.item)" class="text-muted">
                      <i class="fa fa-edit"></i>
                    </a>
                    <a href="#" v-b-modal.modal-delete-role @click.prevent="setSelected(data.item)" class="text-muted">
                      <i class="fa fa-trash"></i>
                    </a>
                  </template>
                </b-table>
              </div>

              <b-col md="6" class="my-1">
                <b-pagination
                  v-model="currentPage"
                  :total-rows="totalRows"
                  :per-page="perPage"
                  class="my-0"
                ></b-pagination>
              </b-col>


            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid Ends-->
    <b-modal id="modal-add-role" size="lg" centered @ok="addRole"
             title="Add New Role" ok-title="Add Role" class="theme-modal">
      <b-form class="needs-validation">
        <div class="form-row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="col-form-label">Display Name</label>
              <input v-model="form.display_name" @keyup="generateSlug" class="form-control"
                     :class="{ 'is-invalid': form.errors.has('display_name') }"
                     required="" type="text">
              <has-error :form="form" field="display_name"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="col-form-label">Name</label>
              <input v-model="form.name" class="form-control" placeholder="must be unique, lowercase and no spaced"
                     :class="{ 'is-invalid': form.errors.has('name') }"
                     required="" type="text">
              <has-error :form="form" field="name"/>
            </div>
          </div>

          <div class="col-12">

            <div class="form-group">
              <label class="col-form-label">Description</label>
              <textarea v-model="form.description" class="form-control"
                        :class="{ 'is-invalid': form.errors.has('description') }"
                        required=""></textarea>
              <has-error :form="form" field="description"/>
            </div>
            <div class="form-group">
              <label class="col-form-label">Permissions</label>
              <multiselect  v-model="form.permissions" placeholder="Select permissions for this role"
                            label="name" track-by="id" :options="permissions" :multiple="true"></multiselect>
              <has-error :form="form" field="permissions"/>
            </div>
          </div>
        </div>
      </b-form>
    </b-modal>
<!--    <b-modal id="modal-edit-role" size="lg" centered @ok="updateRole"-->
<!--             title="Edit Role" ok-title="Update Role" class="theme-modal">-->
<!--      <b-form class="needs-validation" @submit.stop.prevent="updateRole">-->
<!--        <div class="form-row">-->
<!--          <div class="col-md-6">-->
<!--            <div class="form-group">-->
<!--              <label class="col-form-label">Display Name</label>-->
<!--              <input v-model="selected.display_name" @keyup="generateSlug" class="form-control"-->
<!--                     :class="{ 'is-invalid': selected.errors.has('display_name') }"-->
<!--                     required="" type="text">-->
<!--              <has-error :form="selected" field="display_name"/>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="col-md-6">-->
<!--            <div class="form-group">-->
<!--              <label class="col-form-label">Name</label>-->
<!--              <input v-model="selected.name" class="form-control" placeholder="must be unique, lowercase and no spaced"-->
<!--                     :class="{ 'is-invalid': selected.errors.has('name') }"-->
<!--                     required="" type="text">-->
<!--              <has-error :form="selected" field="name"/>-->
<!--            </div>-->
<!--          </div>-->

<!--          <div class="col-12">-->

<!--            <div class="form-group">-->
<!--              <label class="col-form-label">Description</label>-->
<!--              <textarea v-model="selected.description" class="form-control"-->
<!--                        :class="{ 'is-invalid': selected.errors.has('description') }"-->
<!--                        required=""></textarea>-->
<!--              <has-error :form="selected" field="description"/>-->
<!--            </div>-->
<!--            <div class="form-group">-->
<!--              <label class="col-form-label">Permissions</label>-->
<!--              <multiselect  v-model="selected.permissions" placeholder="Select permissions for this role"-->
<!--                            label="name" track-by="id" :options="permissions" :multiple="true"></multiselect>-->
<!--              <has-error :form="selected" field="permissions"/>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </b-form>-->
<!--    </b-modal>-->
    <b-modal id="modal-delete-role" size="sm" centered @ok="deleteRole"
             title="Delete Role" ok-title="Delete" ok-variant="danger" class="theme-modal">
      <div class="row">
        <div class="col-12">
          <p>Are you sure you want to delete this role?</p>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
  import Form from 'vform'
  import Multiselect from 'vue-multiselect'
  import { mapState } from 'vuex';
  import axios from 'axios';

  export default {
    name: "roles",
    middleware: 'auth',
    data() {
      return {
        form: new Form({
          name: "",
          display_name:"",
          description:"",
          permissions:[],
        }),
        formState: {
          name:null,
          display_name:null,

        },
        selected: new Form({
          id:0,
          name:"",
          display_name:"",
          permissions:[],
        }),
        rolesTableFields: [

          {key: 'display_name', label: 'Display Name', sortable: true,},
          {key: 'name', label: 'Name', sortable: true,},
          {key: 'description', label: 'Description', sortable: true,},
          'actions'
        ],

        filter: null,
        currentPage: 1,
        totalRows:0,
        perPage: 10,
        pageOptions: [5, 10, 15],
      }

    },
    components:{
      Multiselect
    },
    created(){
      this.$store.dispatch('management/fetchRoles');
      this.$store.dispatch('management/fetchPermissions');
    },
    mounted() {
      this.totalRows = this.roles.length
    },
    computed: {
      ...mapState({
        roles: state => state.management.roles,
        permissions: state => state.management.permissions
      }),
      sortOptions() {
        // Create an options list from our fields
        return this.tablefields
          .filter(f => f.sortable)
          .map(f => {
            return {text: f.label, value: f.key}
          })
      }
    },
    methods:{
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
      },
      setSelected(item){
        this.selected.id = item.id;
        this.selected.name = item.name;
        this.selected.display_name = item.display_name;
        this.selected.description = item.description;
        this.selected.permissions = item.permissions;

      },
      generateSlug(){
        this.form.name = this.form.display_name.toLowerCase().split(' ').join('-')
        this.selected.name = this.selected.display_name.toLowerCase().split(' ').join('-')
      },
      async addRole(event){
        event.preventDefault();
        try{
          console.log(event);
          this.$bvModal.hide(event.target);
          const { data } = await this.form.post('/api/management/role/add');
          this.$alert(data.message, data.status);
          this.$store.dispatch('management/fetchRoles');
          this.form.name = "";
          this.form.display_name = "";
          this.form.description = "";
          this.form.permissions = [];
        }catch (e) {
          this.$alert(data.message, data.status);
        }


      },
      async updateRole(){
        const { data } = await this.selected.put(`/api/management/role/${this.selected.id}`)
        this.$alert(data.message, data.status);
        this.$store.dispatch('management/fetchRoles');

      },
      async deleteRole(){
        const { data } = await axios.delete(`/api/management/role/${this.selected.id}`)
        this.$alert(data.message, data.status);
        this.$store.dispatch('management/fetchRoles');

      },
    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
