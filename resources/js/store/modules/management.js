import * as types from '../mutations/management'
import axios from "axios";

// state
export const state = {
  roles: {
    page:1,
    perPage:5,
    count:0,
    data:[]
  },
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
  roles: state => state.roles,
  permissions: state => state.permissions
}

// mutations
export const mutations = {
  [types.FETCH_ROLES_SUCCESS] (state, { roles }) {
    state.roles = roles
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

  async fetchAllRoles({commit}){
    try{
      const url = '/api/roles/all'
      const { data } = await axios.get(url)
      commit(types.FETCH_ROLES_SUCCESS, { roles: data })
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

