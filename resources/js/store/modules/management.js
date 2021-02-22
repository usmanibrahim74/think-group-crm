import * as types from '../mutations/management'
import axios from "axios";

// state
export const state = {
  users: {
    page:1,
    perPage:5,
    count:0,
    data:[]
  },
  user:null,

  roles: {
    page:1,
    perPage:5,
    count:0,
    data:[]
  },
  all_roles:[],
  role:null,
  permissions: {
    page:1,
    perPage:5,
    count:0,
    data:[]
  },
  permission:null,
}

// getters
export const getters = {
  users: state => state.users,
  roles: state => state.roles,
  permissions: state => state.permissions
}

// mutations
export const mutations = {
  [types.FETCH_USERS_SUCCESS] (state, { users }) {
    state.users = users
  },
  [types.FETCH_USER_SUCCESS] (state, { user }) {
    state.user = user
  },
  [types.FETCH_ROLES_SUCCESS] (state, { roles }) {
    state.roles = roles
  },
  [types.FETCH_ALL_ROLES_SUCCESS] (state, { roles }) {
    state.all_roles = roles
  },
  [types.FETCH_ROLE_SUCCESS] (state, { role }) {
    state.role = role
  },
  [types.FETCH_PERMISSIONS_SUCCESS] (state, { permissions }) {
    state.permissions = permissions
  },
  [types.FETCH_PERMISSION_SUCCESS] (state, { permission }) {
    state.permission = permission
  }
}

// actions
export const actions = {

  async fetchUsers ({ commit, state }, payload) {
    try {
      let
        page = state.users.page,
        perPage = state.users.perPage,
        search = payload?payload.search?payload.search:"":"";
      const url = '/api/users?'+'search='+search+'&perPage='+perPage+'&page='+page
      const { data } = await axios.get(url)

      commit(types.FETCH_USERS_SUCCESS, { users: data })
    } catch (e) {
      alert('some error occurred');
      console.log(e)
    }
  },



  async fetchUser ({ commit }, { user_id }) {
    try {
      const { data } = await axios.get('/api/users/'+user_id)
      commit(types.FETCH_USER_SUCCESS, { user: data })
    } catch (e) {
      console.error(e)
      // this.$error('some error occured while fetching role');
    }
  },


  async fetchAllRoles({commit}){
    try{
      const url = '/api/roles/all'
      const { data } = await axios.get(url)
      commit(types.FETCH_ALL_ROLES_SUCCESS, { roles: data })
    }catch (e) {

    }
  },

  async fetchRoles ({ commit, state }, payload) {
    try {
      let
        page = state.roles.page,
        perPage = state.roles.perPage,
        search = payload?payload.search?payload.search:"":"";
      const url = '/api/roles?'+'search='+search+'&perPage='+perPage+'&page='+page
      const { data } = await axios.get(url)

      commit(types.FETCH_ROLES_SUCCESS, { roles: data })
    } catch (e) {
      alert('some error occurred');
      console.log(e)
    }
  },



  async fetchRole ({ commit }, { role_id }) {
    try {
      const { data } = await axios.get('/api/roles/'+role_id)
      commit(types.FETCH_ROLE_SUCCESS, { role: data })
    } catch (e) {
      console.error(e)
      // this.$error('some error occured while fetching role');
    }
  },


  async fetchAllPermissions({commit}){
    try{
      const url = '/api/permissions/all'
      const { data } = await axios.get(url)
      commit(types.FETCH_PERMISSIONS_SUCCESS, { permissions: data })
    }catch (e) {

    }
  },

  async fetchPermissions ({ commit, state }, payload) {
    try {
      let
        page = state.permissions.page,
        perPage = state.permissions.perPage,
        search = payload?payload.search?payload.search:"":"";
      const url = '/api/permissions?'+'search='+search+'&perPage='+perPage+'&page='+page
      const { data } = await axios.get(url)

      commit(types.FETCH_PERMISSIONS_SUCCESS, { permissions: data })
    } catch (e) {
      console.log(e)
    }
  },

  async fetchPermission ({ commit }, { permission_id }) {
    try {
      const { data } = await axios.get('/api/permissions/'+permission_id)
      commit(types.FETCH_PERMISSION_SUCCESS, { permission: data })
    } catch (e) {
      console.error(e)
      // this.$error('some error occured while fetching role');
    }
  },
}

