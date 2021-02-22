(window.webpackJsonp=window.webpackJsonp||[]).push([[34],{66:function(e,t,r){"use strict";r.r(t);var s=r(0),a=r.n(s),o=r(3),i=r.n(o),n=r(13),l=r.n(n),c=r(4);r(2);function m(e,t,r,s,a,o,i){try{var n=e[o](i),l=n.value}catch(e){return void r(e)}n.done?t(l):Promise.resolve(l).then(s,a)}function d(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);t&&(s=s.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,s)}return r}function u(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var p={name:"EditRole",middleware:["auth","permission:update-role"],data:function(){return{form:new i.a({name:"",display_name:"",description:"",permissions:[]})}},components:{Multiselect:l.a},created:function(){var e=this.$route.params.id;this.$store.dispatch("management/fetchRole",{role_id:e})},computed:function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?d(Object(r),!0).forEach((function(t){u(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):d(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},Object(c.c)({role:function(e){return e.management.role}})),methods:{generateSlug:function(){this.form.name=this.form.display_name.toLowerCase().split(" ").join("-")},updateRole:function(){var e,t=this;return(e=a.a.mark((function e(){var r,s;return a.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,t.form.put("/api/roles/"+t.role.id);case 3:r=e.sent,s=r.data,t.$alert(s.message,s.status),t.$router.push("/roles"),e.next=12;break;case 9:e.prev=9,e.t0=e.catch(0),console.log(e.t0);case 12:case"end":return e.stop()}}),e,null,[[0,9]])})),function(){var t=this,r=arguments;return new Promise((function(s,a){var o=e.apply(t,r);function i(e){m(o,s,a,i,n,"next",e)}function n(e){m(o,s,a,i,n,"throw",e)}i(void 0)}))})()}},watch:{role:{deep:!0,handler:function(){this.form.name=this.role.name,this.form.display_name=this.role.display_name,this.form.description=this.role.description,this.form.permissions=this.role.permissions}}}},f=(r(15),r(1)),v=Object(f.a)(p,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",[r("Breadcrumbs",{attrs:{title:"Edit Role",main:"Roles"}}),e._v(" "),r("div",{staticClass:"container-fluid"},[r("div",{staticClass:"row"},[r("div",{staticClass:"col-md-12"},[r("div",{staticClass:"card"},[e._m(0),e._v(" "),r("div",{staticClass:"card-body"},[r("b-form",{staticClass:"needs-validation",on:{submit:function(t){return t.preventDefault(),e.updateRole(t)}}},[r("div",{staticClass:"form-row"},[r("div",{staticClass:"col"},[r("div",{staticClass:"form-group row"},[r("label",{staticClass:"col-form-label col-sm-3"},[e._v("Display Name:")]),e._v(" "),r("div",{staticClass:"col-sm-9"},[r("input",{directives:[{name:"model",rawName:"v-model",value:e.form.display_name,expression:"form.display_name"}],staticClass:"form-control",class:{"is-invalid":e.form.errors.has("display_name")},attrs:{type:"text"},domProps:{value:e.form.display_name},on:{keyup:e.generateSlug,input:function(t){t.target.composing||e.$set(e.form,"display_name",t.target.value)}}}),e._v(" "),r("has-error",{attrs:{form:e.form,field:"display_name"}})],1)]),e._v(" "),r("div",{staticClass:"form-group row"},[r("label",{staticClass:"col-form-label col-sm-3"},[e._v("Name:")]),e._v(" "),r("div",{staticClass:"col-sm-9"},[r("input",{directives:[{name:"model",rawName:"v-model",value:e.form.name,expression:"form.name"}],staticClass:"form-control",class:{"is-invalid":e.form.errors.has("name")},attrs:{placeholder:"must be unique, lowercase and no spaced",type:"text"},domProps:{value:e.form.name},on:{input:function(t){t.target.composing||e.$set(e.form,"name",t.target.value)}}}),e._v(" "),r("has-error",{attrs:{form:e.form,field:"name"}})],1)]),e._v(" "),r("div",{staticClass:"form-group row"},[r("label",{staticClass:"col-form-label col-sm-3"},[e._v("Description:")]),e._v(" "),r("div",{staticClass:"col-sm-9"},[r("textarea",{directives:[{name:"model",rawName:"v-model",value:e.form.description,expression:"form.description"}],staticClass:"form-control",class:{"is-invalid":e.form.errors.has("description")},attrs:{required:""},domProps:{value:e.form.description},on:{input:function(t){t.target.composing||e.$set(e.form,"description",t.target.value)}}}),e._v(" "),r("has-error",{attrs:{form:e.form,field:"description"}})],1)])]),e._v(" "),r("div",{staticClass:"col-12 text-right"},[r("v-button",{attrs:{loading:e.form.busy}},[e._v("Update Role")])],1)])])],1)])])])])],1)}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"card-header"},[t("h5",[this._v("Edit Role")]),t("span",[this._v("Update this role")])])}],!1,null,null,null);t.default=v.exports}}]);