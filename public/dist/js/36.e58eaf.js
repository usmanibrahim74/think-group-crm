(window.webpackJsonp=window.webpackJsonp||[]).push([[36],{68:function(s,t,r){"use strict";r.r(t);var o=r(0),a=r.n(o),n=r(3),e=r.n(n);function i(s,t,r,o,a,n,e){try{var i=s[n](e),c=i.value}catch(s){return void r(s)}i.done?t(c):Promise.resolve(c).then(o,a)}var c={scrollToTop:!1,metaInfo:function(){return{title:"Change Password"}},data:function(){return{form:new e.a({password:"",password_confirmation:""})}},methods:{update:function(){var s,t=this;return(s=a.a.mark((function s(){return a.a.wrap((function(s){for(;;)switch(s.prev=s.next){case 0:return s.next=2,t.form.patch("/api/settings/password");case 2:t.$success(t.$t("password_updated")),t.form.reset();case 4:case"end":return s.stop()}}),s)})),function(){var t=this,r=arguments;return new Promise((function(o,a){var n=s.apply(t,r);function e(s){i(n,o,a,e,c,"next",s)}function c(s){i(n,o,a,e,c,"throw",s)}e(void 0)}))})()}}},d=r(1),l=Object(d.a)(c,(function(){var s=this,t=s.$createElement,r=s._self._c||t;return r("div",[r("Breadcrumbs",{attrs:{title:"Change Password",main:"Settings"}}),s._v(" "),r("div",{staticClass:"container-fluid"},[r("div",{staticClass:"row"},[r("div",{staticClass:"col-md-12"},[r("div",{staticClass:"card"},[s._m(0),s._v(" "),r("div",{staticClass:"card-body"},[r("form",{on:{submit:function(t){return t.preventDefault(),s.update(t)},keydown:function(t){return s.form.onKeydown(t)}}},[r("div",{staticClass:"form-group row"},[r("label",{staticClass:"col-md-3 col-form-label text-md-right"},[s._v(s._s(s.$t("new_password")))]),s._v(" "),r("div",{staticClass:"col-md-7"},[r("input",{directives:[{name:"model",rawName:"v-model",value:s.form.password,expression:"form.password"}],staticClass:"form-control",class:{"is-invalid":s.form.errors.has("password")},attrs:{type:"password",name:"password"},domProps:{value:s.form.password},on:{input:function(t){t.target.composing||s.$set(s.form,"password",t.target.value)}}}),s._v(" "),r("has-error",{attrs:{form:s.form,field:"password"}})],1)]),s._v(" "),r("div",{staticClass:"form-group row"},[r("label",{staticClass:"col-md-3 col-form-label text-md-right"},[s._v(s._s(s.$t("confirm_password")))]),s._v(" "),r("div",{staticClass:"col-md-7"},[r("input",{directives:[{name:"model",rawName:"v-model",value:s.form.password_confirmation,expression:"form.password_confirmation"}],staticClass:"form-control",class:{"is-invalid":s.form.errors.has("password_confirmation")},attrs:{type:"password",name:"password_confirmation"},domProps:{value:s.form.password_confirmation},on:{input:function(t){t.target.composing||s.$set(s.form,"password_confirmation",t.target.value)}}}),s._v(" "),r("has-error",{attrs:{form:s.form,field:"password_confirmation"}})],1)]),s._v(" "),r("div",{staticClass:"form-group row"},[r("div",{staticClass:"col-md-9 ml-md-auto"},[r("v-button",{attrs:{loading:s.form.busy,type:"primary"}},[s._v("\n                      "+s._s(s.$t("update"))+"\n                    ")])],1)])])])])])])])],1)}),[function(){var s=this.$createElement,t=this._self._c||s;return t("div",{staticClass:"card-header"},[t("h5",[this._v("Change Password")]),this._v(" "),t("span",[this._v("Change your account password")])])}],!1,null,null,null);t.default=l.exports}}]);