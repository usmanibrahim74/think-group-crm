<template>
  <!-- Right sidebar Start-->
  <div class="right-sidebar" :class="{ show:rightsidebar_toggle_var }" id="right_side_bar">
    <div>
      <div class="container p-0">
        <div class="modal-header p-l-20 p-r-20">
          <div class="col-sm-8 p-0">
            <h6 class="modal-title font-weight-bold">FRIEND LIST</h6>
          </div>
          <div class="col-sm-4 text-right p-0">
            <feather class="mr-2" type="settings"></feather>
          </div>
        </div>
      </div>
      <div class="friend-list-search mt-0">
        <input
          v-model="search"
          v-on:keyup="setSerchUsers"
          class="form-control"
          type="text"
          placeholder="search"
        />
        <i class="fa fa-search"></i>
      </div>
      <div class="p-l-30 p-r-30">
        <div class="chat-box">
          <div class="people-list friend-list custom-scrollbar">
            <ul class="list" v-if="search == ''">
              <router-link :to="'/app/chat'">
              <li
                class="clearfix"
                v-for="(user,index) in activeusers"
                :key="index"
              >
                <img class="rounded-circle user-image" :src="getImgUrl(user.thumb)" alt />
                <div class="status-circle away"></div>
                <div class="about">
                  <div class="name">{{user.name}}</div>
                  <div class="status">{{user.status}}</div>
                </div>
              </li>
              </router-link>
            </ul>
            <ul class="list" v-if="search != ''">
              <router-link :to="'/app/chat'">
              <li
                class="clearfix"
                v-for="(user,index) in serchUser"
                :key="index"
              >
                <img class="rounded-circle user-image" :src="getImgUrl(user.thumb)" alt />
                <div class="status-circle away"></div>
                <div class="about">
                  <div class="name">{{user.name}}</div>
                  <div class="status">{{user.status}}</div>
                </div>
              </li>
              </router-link>
              <div v-if="!serchUser.length">
                <div class="search-not-found chat-search text-center">
                  <div>
                    <img src="../assets/images/search-not-found.png" alt class="second-search" />
                    <p>Sorry, We didn't find any results matching this search</p>
                  </div>
                </div>
              </div>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Right sidebar Ends-->
</template>
<script>
import { mapState } from "vuex";
export default {
  name: "rightsidebar",
  props: ["rightsidebar_toggle_var", "msg"],
  data() {
    return {
      search: ""
    };
  },
  computed: {
    ...mapState({
      users: state => state.chat.users.filter(function (user) {
          if (user.id != 0)
              return user;
      }),
      activeusers: state =>
      state.chat.users.filter(function(user) {
        if (user.active == "active" && user.id != 0) return user;
      }),
      serchUser: state => state.chat.serchUser
       }),
   
  },
  methods: {
    getImgUrl(path) {
            return require('../assets/images/'+path)
        },
    setSerchUsers: function() {
      if (this.search != "")
        this.$store.dispatch("chat/setSerchUsers", this.search);
    }
  }
};
</script>
