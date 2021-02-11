<template>
  <div id="app">
    <loading ref="loading" />
    <div class="loader-wrapper"  :class="{'loderhide' : !show }">
      <div class="loader bg-white">
        <div class="whirly-loader"> </div>
      </div>
    </div>
    <transition name="page" mode="out-in">
      <component :is="layout" v-if="layout" />
    </transition>
  </div>
</template>

<script>
import Loading from './components-spa/Loading'

// Load layout components dynamically.
const requireContext = require.context('~/layouts', false, /.*\.vue$/)

const layouts = requireContext.keys()
  .map(file =>
    [file.replace(/(^.\/)|(\.vue$)/g, ''), requireContext(file)]
  )
  .reduce((components, [name, component]) => {
    components[name] = component.default || component
    return components
  }, {})

export default {
  el: '#app',

  components: {
    Loading
  },

  data: () => ({
    layout: null,
    defaultLayout: 'app',
    show:true,
  }),

  metaInfo () {
    const { appName } = window.config

    return {
      title: appName,
      titleTemplate: `%s · ${appName}`
    }
  },

  mounted () {
    this.$loading = this.$refs.loading
    this.timeOut();
  },

  methods: {
    /**
     * Set the application layout.
     *
     * @param {String} layout
     */
    setLayout (layout) {
      if (!layout || !layouts[layout]) {
        layout = this.defaultLayout
      }

      this.layout = layouts[layout]
    },

    /**
    * hide the loader
    *
    */
    timeOut(){
      setTimeout(()=>{
        this.show = false;
      }, 1000);
    }
  }
}
</script>

<style lang="scss" scoped>
  .loader-wrapper.loderhide {
    display: none;
  }
</style>
