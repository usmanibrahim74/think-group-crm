import * as types from '../mutations/employers'
import axios from "axios";
import Form from "vform";
// state
export const state = {
  profiles: {
    page:1,
    perPage:5,
    count:0,
    data:[]
  },
  profile:null,
  accounts: {
    page:1,
    perPage:5,
    count:0,
    data:[]
  },
  account:null,
  all_profiles:[],
}

// getters
export const getters = {
  profiles: state => state.profiles,
  accounts: state => state.accounts,

}

// mutations
export const mutations = {
  [types.FETCH_ALL_PROFILES_SUCCESS] (state, { profiles }) {
    state.all_profiles = profiles
  },
  [types.FETCH_PROFILES_SUCCESS] (state, { profiles }) {
    state.profiles = profiles
  },
  [types.FETCH_PROFILE_SUCCESS] (state, { profile }) {
    state.profile = profile
  },
  [types.FETCH_ACCOUNTS_SUCCESS] (state, { accounts }) {
    state.accounts = accounts
  },
  [types.FETCH_ACCOUNT_SUCCESS] (state, { account }) {
    state.account = account
  },
}

// actions
export const actions = {


  async fetchProfiles ({ commit, state }, payload) {
    try {
      let
        page = state.profiles.page,
        perPage = state.profiles.perPage,
        search = payload?payload.search?payload.search:"":"";
      const url = '/api/employers/profiles?'+'search='+search+'&perPage='+perPage+'&page='+page
      const { data } = await axios.get(url)

      commit(types.FETCH_PROFILES_SUCCESS, { profiles : data });
    } catch (e) {
      alert('some error occurred');
      console.log(e)
    }
  },

  async fetchAllProfiles({commit}){
    try {
      const { data } = await axios.get('/api/employers/profiles/all')
      commit(types.FETCH_ALL_PROFILES_SUCCESS, { profiles: data })
    } catch (e) {
      console.error(e)
    }
  },

  async fetchProfile ({ commit }, { employer_id }) {
    try {
      const { data } = await axios.get('/api/employers/profiles/'+employer_id)
      commit(types.FETCH_PROFILE_SUCCESS, { profile: data })
    } catch (e) {
      console.error(e)
    }
  },

  async fetchAccounts ({ commit, state }, payload) {
    try {
      let
        page = state.accounts.page,
        perPage = state.accounts.perPage,
        search = payload?payload.search?payload.search:"":"";
      const url = '/api/employers/accounts?'+'search='+search+'&perPage='+perPage+'&page='+page
      const { data } = await axios.get(url)

      commit(types.FETCH_ACCOUNTS_SUCCESS, { accounts: data })
    } catch (e) {
      alert('some error occurred');
      console.log(e)
    }
  },

  async fetchAccount ({ commit }, { account_id }) {
    try {
      const { data } = await axios.get('/api/employers/accounts/'+account_id)
      commit(types.FETCH_ACCOUNT_SUCCESS, { account: data })
    } catch (e) {
      console.error(e)
    }
  },

}

