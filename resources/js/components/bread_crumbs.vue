<template>
  <div class="container-fluid">
    <div class="page-header">
      <div class="row ">
        <div class="col-lg-11">
          <div class="page-header-left">
            <h3>{{title}}</h3>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><router-link :to="{ path: '/dashboard/default' }"><feather type="home"></feather></router-link></li>
              <li class="breadcrumb-item" v-if="main">{{main}}</li>
              <li class="breadcrumb-item active" v-if="title">{{title}}</li>
            </ol>
          </div>
        </div>
        <div class="col-lg-1">
          <img class="w-100" v-if="user && user.profile" :src="'/storage/'+user.profile.logo" alt />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Bookmark from  './bookmark'
import { mapState } from "vuex"
export default {
  props: {
    title: {
      default: 'home'
    },
    main: {
      default: ''
    }
  },
  computed:{
    ...mapState({
      user: state => state.auth.user
    })
  },
  components:{
    Bookmark
  }
}
</script>
