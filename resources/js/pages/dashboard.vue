<template>
  <div>
    <Breadcrumbs title="Dashboard"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row" v-if="user && user.profile">
        <div class="col-4 offset-4">
          <img class="w-100" :src="'/storage/'+user.profile.logo" alt />
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6 col-xl-6 col-lg-6" v-for="(widget,i) in widgets">
          <div class="card o-hidden">
            <div class="bg-primary b-r-4 card-body">
              <div class="media static-top-widget">
                <div class="align-self-center text-center">
                  <feather :type="widget.logo" class="middle"></feather>
                </div>
                <div class="media-body"><span class="m-0">
                  {{ widget.name }}
                </span>
                  <h4 class="mb-0 counter">
                    {{ widget.value }}
                  </h4>
                  <feather :type="widget.logo" class="icon-bg"></feather>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid Ends-->
  </div>
</template>

<script>

    import { mapState } from 'vuex';
    import axios from 'axios';
    export default {
        name: "dashboard",
        middleware: ['auth','permission:access-dashboard'],
        data(){
          return {
            widgets : []
          }
        },
        created(){
          this.fetchDashboardWidgets();
        },
        computed:{
          ...mapState({
            user: state => state.auth.user
          })
        },
        methods:{
          async fetchDashboardWidgets(){
            const { data } = await axios.get('/api/dashboard');
            this.widgets = data;
          }
        }
    }
</script>

<style scoped>

</style>
