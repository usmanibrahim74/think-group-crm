import Vue from 'vue'
// import body from './body'
// import bookmark from './bookmark'
// import bread_crumbs from './bread_crumbs'
// import customizer from './customizer'
// import header from './header'
// import footer from './footer'
// import main from './main'
// import right_sidebar from './right_sidebar'
// import sidebar from './sidebar'
import Child from './Child'
import Button from './Button'
import Checkbox from './Checkbox'
import { HasError, AlertError, AlertSuccess } from 'vform'

// Components that are registered globaly.
[
  // body,
  // bookmark,
  // bread_crumbs,
  // customizer,
  // header,
  // footer,
  // main,
  // right_sidebar,
  // sidebar,
  Child,
  Button,
  Checkbox,
  HasError,
  AlertError,
  AlertSuccess
].forEach(Component => {
  Vue.component(Component.name, Component)
})
