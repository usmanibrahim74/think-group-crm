(window.webpackJsonp=window.webpackJsonp||[]).push([[26],{55:function(r,e,o){"use strict";o.r(e);var t=o(0),s=o.n(t),a=o(3),i=o.n(a),l=(o(2),o(4)),n=o(13);function c(r,e,o,t,s,a,i){try{var l=r[a](i),n=l.value}catch(r){return void o(r)}l.done?e(n):Promise.resolve(n).then(t,s)}function m(r,e){var o=Object.keys(r);if(Object.getOwnPropertySymbols){var t=Object.getOwnPropertySymbols(r);e&&(t=t.filter((function(e){return Object.getOwnPropertyDescriptor(r,e).enumerable}))),o.push.apply(o,t)}return o}function f(r,e,o){return e in r?Object.defineProperty(r,e,{value:o,enumerable:!0,configurable:!0,writable:!0}):r[e]=o,r}var d={name:"CreateEmployerAccount",middleware:["auth","permission:add-employer-account"],data:function(){return{form:new i.a({profile:null,name:"",email:"",password:"",password_confirmation:""})}},components:{Multiselect:o.n(n).a},created:function(){this.$store.dispatch("employers/fetchAllProfiles")},computed:function(r){for(var e=1;e<arguments.length;e++){var o=null!=arguments[e]?arguments[e]:{};e%2?m(Object(o),!0).forEach((function(e){f(r,e,o[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(r,Object.getOwnPropertyDescriptors(o)):m(Object(o)).forEach((function(e){Object.defineProperty(r,e,Object.getOwnPropertyDescriptor(o,e))}))}return r}({},Object(l.c)({profiles:function(r){return r.employers.all_profiles}})),methods:{add:function(){var r,e=this;return(r=s.a.mark((function r(){var o,t;return s.a.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.prev=0,console.log(e.form),r.next=4,e.form.post("/api/employers/accounts");case 4:o=r.sent,t=o.data,e.$alert(t.message,t.status),e.$router.push("/employers/accounts"),r.next=14;break;case 10:r.prev=10,r.t0=r.catch(0),console.log(r.t0),e.$error("some error occurred");case 14:case"end":return r.stop()}}),r,null,[[0,10]])})),function(){var e=this,o=arguments;return new Promise((function(t,s){var a=r.apply(e,o);function i(r){c(a,t,s,i,l,"next",r)}function l(r){c(a,t,s,i,l,"throw",r)}i(void 0)}))})()}}},p=(o(15),o(1)),u=Object(p.a)(d,(function(){var r=this,e=r.$createElement,o=r._self._c||e;return o("div",[o("Breadcrumbs",{attrs:{title:"Add Employer Account",main:"Employers"}}),r._v(" "),o("div",{staticClass:"container-fluid"},[o("div",{staticClass:"row"},[o("div",{staticClass:"col-md-12"},[o("div",{staticClass:"card"},[r._m(0),r._v(" "),o("div",{staticClass:"card-body"},[o("b-form",{staticClass:"needs-validation",on:{submit:function(e){return e.preventDefault(),r.add(e)}}},[o("div",{staticClass:"form-row"},[o("div",{staticClass:"col-12"},[o("div",{staticClass:"form-group row"},[o("label",{staticClass:"col-form-label col-sm-3"},[r._v("Select Employer:")]),r._v(" "),o("div",{staticClass:"col-sm-9"},[o("multiselect",{class:{"is-invalid":r.form.errors.has("profile")},attrs:{placeholder:"Select Employer",label:"name","track-by":"id",options:r.profiles},model:{value:r.form.profile,callback:function(e){r.$set(r.form,"profile",e)},expression:"form.profile"}}),r._v(" "),o("has-error",{attrs:{form:r.form,field:"profile"}})],1)]),r._v(" "),o("div",{staticClass:"form-group row"},[o("label",{staticClass:"col-form-label col-sm-3"},[r._v("Name:")]),r._v(" "),o("div",{staticClass:"col-sm-9"},[o("input",{directives:[{name:"model",rawName:"v-model",value:r.form.name,expression:"form.name"}],staticClass:"form-control",class:{"is-invalid":r.form.errors.has("name")},attrs:{type:"text"},domProps:{value:r.form.name},on:{input:function(e){e.target.composing||r.$set(r.form,"name",e.target.value)}}}),r._v(" "),o("has-error",{attrs:{form:r.form,field:"name"}})],1)]),r._v(" "),o("div",{staticClass:"form-group row"},[o("label",{staticClass:"col-form-label col-sm-3"},[r._v("Email:")]),r._v(" "),o("div",{staticClass:"col-sm-9"},[o("input",{directives:[{name:"model",rawName:"v-model",value:r.form.email,expression:"form.email"}],staticClass:"form-control",class:{"is-invalid":r.form.errors.has("email")},attrs:{type:"email"},domProps:{value:r.form.email},on:{input:function(e){e.target.composing||r.$set(r.form,"email",e.target.value)}}}),r._v(" "),o("has-error",{attrs:{form:r.form,field:"email"}})],1)]),r._v(" "),o("div",{staticClass:"form-group row"},[o("label",{staticClass:"col-form-label col-sm-3"},[r._v("Password:")]),r._v(" "),o("div",{staticClass:"col-sm-9"},[o("input",{directives:[{name:"model",rawName:"v-model",value:r.form.password,expression:"form.password"}],staticClass:"form-control",class:{"is-invalid":r.form.errors.has("password")},attrs:{type:"password"},domProps:{value:r.form.password},on:{input:function(e){e.target.composing||r.$set(r.form,"password",e.target.value)}}}),r._v(" "),o("has-error",{attrs:{form:r.form,field:"password"}})],1)]),r._v(" "),o("div",{staticClass:"form-group row"},[o("label",{staticClass:"col-form-label col-sm-3"},[r._v("Confirm Password:")]),r._v(" "),o("div",{staticClass:"col-sm-9"},[o("input",{directives:[{name:"model",rawName:"v-model",value:r.form.password_confirmation,expression:"form.password_confirmation"}],staticClass:"form-control",class:{"is-invalid":r.form.errors.has("password_confirmation")},attrs:{type:"password"},domProps:{value:r.form.password_confirmation},on:{input:function(e){e.target.composing||r.$set(r.form,"password_confirmation",e.target.value)}}}),r._v(" "),o("has-error",{attrs:{form:r.form,field:"password_confirmation"}})],1)])]),r._v(" "),o("div",{staticClass:"col-12 text-right"},[o("v-button",{attrs:{loading:r.form.busy}},[r._v("Add Account")])],1)])])],1)])])])])],1)}),[function(){var r=this.$createElement,e=this._self._c||r;return e("div",{staticClass:"card-header"},[e("h5",[this._v("Add Employer Account")]),e("span",[this._v("Add a new employer account")])])}],!1,null,null,null);e.default=u.exports}}]);