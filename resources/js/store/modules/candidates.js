import * as types from '../mutations/candidates'
import axios from "axios";
import Form from "vform";
// state
export const state = {
  candidates: {
    page:1,
    perPage:5,
    count:0,
    data:[]
  },
  shortlisted: {
    page:1,
    perPage:5,
    count:0,
    data:[]
  },
  shortlistedBy: {
    page:1,
    perPage:5,
    count:0,
    data:[]
  },
  candidate:null,
  industries: {
    page:1,
    perPage:5,
    count:0,
    data:[]
  },
  all_industries:[],
}

// getters
export const getters = {
  industries: state => state.industries,
  candidates: state => state.candidates
}

// mutations
export const mutations = {
  [types.FETCH_SHORTLISTED_CANDIDATES_SUCCESS] (state, { candidates }) {
    state.shortlisted = candidates
  },
  [types.FETCH_SHORTLISTED_BY_SUCCESS] (state, { employers }) {
    state.shortlistedBy = employers
  },
  [types.FETCH_CANDIDATES_SUCCESS] (state, { candidates }) {
    state.candidates = candidates
  },
  [types.FETCH_CANDIDATE_SUCCESS] (state, { candidate }) {
    state.candidate = candidate
  },
  [types.FETCH_INDUSTRIES_SUCCESS] (state, { industries }) {
    state.industries = industries
  },
  [types.FETCH_ALL_INDUSTRIES_SUCCESS] (state, { industries }) {
    state.all_industries = industries
  },
}

// actions
export const actions = {


  async fetchShortlisted ({ commit, state }, payload) {
    try {
      let
        page = state.shortlisted.page,
        perPage = state.shortlisted.perPage,
        search = payload?payload.search?payload.search:"":"";
      const url = '/api/candidates/shortlisted?'+'search='+search+'&perPage='+perPage+'&page='+page
      const { data } = await axios.get(url)

      commit(types.FETCH_SHORTLISTED_CANDIDATES_SUCCESS, { candidates: data })
    } catch (e) {

      console.log(e)
    }
  },
  async fetchShortlistedBy ({ commit, state }, payload) {
    try {
      let
        page = state.shortlistedBy.page,
        perPage = state.shortlistedBy.perPage,
        search = payload?payload.search?payload.search:"":"",
        id =  payload.id;
      const url = '/api/candidates/'+id+'/shortlistedby?'+'search='+search+'&perPage='+perPage+'&page='+page
      const { data } = await axios.get(url)

      commit(types.FETCH_SHORTLISTED_BY_SUCCESS, { employers: data })
    } catch (e) {

      console.log(e)
    }
  },

  async fetchCandidates ({ commit, state }, payload) {
    try {
      state.candidates.data = [];
      let
        page = state.candidates.page,
        perPage = state.candidates.perPage,
        search = payload?payload.search?payload.search:"":"";
      let department = payload.department?payload.department:'';
      const url = '/api/candidates?'+'search='+search+'&perPage='+perPage+'&page='+page+"&department="+department
      const { data } = await axios.get(url)

      commit(types.FETCH_CANDIDATES_SUCCESS, { candidates: data })
    } catch (e) {

      console.log(e)
    }
  },

  async fetchCandidate ({ commit }, { candidate_id }) {
    try {
      const { data } = await axios.get('/api/candidates/'+candidate_id)
      commit(types.FETCH_CANDIDATE_SUCCESS, { candidate: data })
    } catch (e) {
      console.error(e)
    }
  },

  async fetchIndustries ({ commit, state }, payload) {
    try {
      let
        page = state.industries.page,
        perPage = state.industries.perPage,
        search = payload?payload.search?payload.search:"":"";
      const url = '/api/industries?'+'search='+search+'&perPage='+perPage+'&page='+page
      const { data } = await axios.get(url)

      commit(types.FETCH_INDUSTRIES_SUCCESS, { industries: data })
    } catch (e) {
      alert('some error occurred');
      console.log(e)
    }
  },

  async fetchAllIndustries ({ commit }) {
    try {
      const url = '/api/industries/all'
      const { data } = await axios.get(url)

      commit(types.FETCH_ALL_INDUSTRIES_SUCCESS, { industries: data })
    } catch (e) {
      alert('some error occurred');
      console.log(e)
    }
  },

}

