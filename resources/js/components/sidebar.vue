<template>
  <div>
    <div class="main-header-left d-none d-lg-block">
      <div class="logo-wrapper">
        <router-link to="/">
          <img src="../assets/images/endless-logo.png" alt />
        </router-link>
      </div>
    </div>
    <div class="sidebar custom-scrollbar">

      <ul
        class="sidebar-menu"
        id="myDIV"
        :style="[layout.settings.sidebar.type == 'horizontal_sidebar' ? layout.settings.layout_type=='rtl'? {'margin-right': margin+'px'} : {'margin-left': margin+'px'} :  { margin : '0px'}]"
      >
        <li
          class="left-arrow"
          :class="{'d-none': layout.settings.layout_type=='rtl'? hideLeftArrowRTL: hideLeftArrow}"
          @click="(layout.settings.sidebar.type === 'horizontal_sidebar' && layout.settings.layout_type==='rtl') ? scrollToLeftRTL() : scrollToLeft()"
        >
          <i class="fa fa-angle-left"></i>
        </li>
        <li
          v-for="(menuItem, index) in menuItems"
          :key="index"
          :class="{'active': menuItem.active}"
        >
          <!-- Sub -->
          <a
            href="javascript:void(0)"
            class="sidebar-header"
            v-if="menuItem.type == 'sub'"
            @click="setNavActive(menuItem, index)"
          >
            <feather :type="menuItem.icon" class="top"></feather>
            <span>
              {{menuItem.title}}
              <span
                :class="'badge badge-pill badge-'+menuItem.badgeType"
                v-if="menuItem.badgeType"
              >{{menuItem.badgeValue}}</span>
            </span>
            <i class="fa fa-angle-right pull-right" v-if="menuItem.children"></i>
          </a>
          <!-- Link -->
          <router-link
            :to="menuItem.path"
            class="sidebar-header"
            v-if="menuItem.type == 'link'"
            router-link-exact-active
          >
            <feather :type="menuItem.icon" class="top"></feather>
            <span>
              {{menuItem.title}}
              <span
                :class="'badge badge-'+menuItem.badgeType+' ml-3'"
                v-if="menuItem.badgeType"
              >{{menuItem.badgeValue}}</span>
            </span>
            <i class="fa fa-angle-right pull-right" v-if="menuItem.children"></i>
          </router-link>
          <!-- External Link -->
          <a
            :href="menuItem.path"
            class="sidebar-header"
            v-if="menuItem.type == 'extLink'"
            @click="setNavActive(menuItem, index)"
          >
            <feather :type="menuItem.icon" class="top"></feather>
            <span>
              {{menuItem.title}}
              <span
                :class="'badge badge-'+menuItem.badgeType+' ml-3'"
                v-if="menuItem.badgeType"
              >{{menuItem.badgeValue}}</span>
            </span>
            <i class="fa fa-angle-right pull-right" v-if="menuItem.children"></i>
          </a>
          <!-- External Tab Link -->
          <a
            :href="menuItem.path"
            target="_blank"
            class="sidebar-header"
            v-if="menuItem.type == 'extTabLink'"
            @click="setNavActive(menuItem, index)"
          >
            <feather :type="menuItem.icon" class="top"></feather>
            <span>
              {{menuItem.title}}
              <span
                :class="'badge badge-'+menuItem.badgeType+' ml-3'"
                v-if="menuItem.badgeType"
              >{{menuItem.badgeValue}}</span>
            </span>
            <i class="fa fa-angle-right pull-right" v-if="menuItem.children"></i>
          </a>
          <!-- 2nd Level Menu -->
          <ul class="sidebar-submenu" v-if="menuItem.children">
            <li
              v-for="(childrenItem, index) in menuItem.children"
              :key="index"
              :class="{'active': childrenItem.active}"
            >
              <!-- Sub -->
              <a
                href="javascript:void(0)"
                v-if="childrenItem.type == 'sub'"
                @click="setNavActive(childrenItem, index)"
              >
                <i class="fa fa-circle"></i>
                {{childrenItem.title}}
                <span
                  :class="'badge badge-'+childrenItem.badgeType+' pull-right'"
                  v-if="childrenItem.badgeType"
                >{{childrenItem.badgeValue}}</span>
                <i class="fa fa-angle-right pull-right" v-if="childrenItem.children"></i>
              </a>
              <!-- Link -->
              <router-link
                :to="childrenItem.path"
                v-if="childrenItem.type == 'link'"
                router-link-exact-active
              >
                <i class="fa fa-circle"></i>
                {{childrenItem.title}}
                <span
                  :class="'badge badge-'+childrenItem.badgeType+' pull-right'"
                  v-if="childrenItem.badgeType"
                >{{childrenItem.badgeValue}}</span>
                <i class="fa fa-angle-right pull-right" v-if="childrenItem.children"></i>
              </router-link>
              <!-- External Link -->
              <a :href="childrenItem.path" v-if="childrenItem.type == 'extLink'">
                <i class="fa fa-circle"></i>
                {{childrenItem.title}}
                <span
                  :class="'badge badge-'+childrenItem.badgeType+' pull-right'"
                  v-if="childrenItem.badgeType"
                >{{childrenItem.badgeValue}}</span>
                <i class="fa fa-angle-right pull-right" v-if="childrenItem.children"></i>
              </a>
              <!-- External Tab Link -->
              <a :href="childrenItem.path" target="_blank" v-if="childrenItem.type == 'extTabLink'">
                <i class="fa fa-circle"></i>
                {{childrenItem.title}}
                <span
                  :class="'badge badge-'+childrenItem.badgeType+' pull-right'"
                  v-if="childrenItem.badgeType"
                >{{childrenItem.badgeValue}}</span>
                <i class="fa fa-angle-right pull-right" v-if="childrenItem.children"></i>
              </a>
              <!-- 3rd Level Menu -->
              <ul class="sidebar-submenu horizontal-child-sub" v-if="childrenItem.children">
                <li v-for="(childrenSubItem, index) in childrenItem.children" :key="index">
                  <!-- Link -->
                  <router-link
                    :to="childrenSubItem.path"
                    v-if="childrenSubItem.type == 'link'"
                    router-link-exact-active
                  >
                    <i class="fa fa-circle"></i>
                    {{childrenSubItem.title}}
                    <span
                      :class="'badge badge-'+childrenSubItem.badgeType+' pull-right'"
                      v-if="childrenSubItem.badgeType"
                    >{{childrenSubItem.badgeValue}}</span>
                    <i class="fa fa-angle-right pull-right" v-if="childrenSubItem.children"></i>
                  </router-link>
                  <!-- External Link -->
                  <a :href="childrenSubItem.path" v-if="childrenSubItem.type == 'extLink'">
                    <i class="fa fa-circle"></i>
                    {{childrenSubItem.title}}
                    <span
                      :class="'badge badge-'+childrenSubItem.badgeType+' pull-right'"
                      v-if="childrenSubItem.badgeType"
                    >{{childrenSubItem.badgeValue}}</span>
                    <i class="fa fa-angle-right pull-right" v-if="childrenSubItem.children"></i>
                  </a>
                  <!-- External Tab Link -->
                  <a
                    :href="childrenSubItem.path"
                    target="_blank"
                    v-if="childrenSubItem.type == 'extTabLink'"
                  >
                    <i class="fa fa-circle"></i>
                    {{childrenSubItem.title}}
                    <span
                      :class="'badge badge-'+childrenSubItem.badgeType+' pull-right'"
                      v-if="childrenSubItem.badgeType"
                    >{{childrenSubItem.badgeValue}}</span>
                    <i class="fa fa-angle-right pull-right" v-if="childrenSubItem.children"></i>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        <li
          class="right-arrow"
          :class="{'d-none': layout.settings.layout_type=='rtl'? hideRightArrowRTL : hideRightArrow }"
          @click="(layout.settings.sidebar.type == 'horizontal_sidebar' && layout.settings.layout_type=='rtl') ? scrollToRightRTL() : scrollToRight()"
        >
          <i class="fa fa-angle-right"></i>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
export default {
  name: "Sidebar",
  data() {
    return {
      width: 0,
      height: 0,
      margin: 0,
      hideRightArrowRTL: true,
      hideLeftArrowRTL: true,
      hideRightArrow: true,
      hideLeftArrow: true,
      menuWidth: 0
    };
  },
  computed: {
    ...mapState({
      menuItems: state => state.menu.data,
      layout: state => state.layout.layout,
      sidebar: state => state.layout.sidebarType
    })
  },
  created() {
    window.addEventListener("resize", this.handleResize);
    this.handleResize();
    if (this.width < 991) {
      this.layout.settings.sidebar.type = "default";
    }
    const val  = this.sidebar
      if (val == 'default') {
				this.layout.settings.sidebar.type = 'default';
				this.layout.settings.sidebar.body_type = 'default';
			} else if (val == 'compact') {
				this.layout.settings.sidebar.type = 'compact-wrapper';
				this.layout.settings.sidebar.body_type = 'sidebar-icon';
			} else if (val == 'compact-icon') {
				this.layout.settings.sidebar.type = 'compact-page';
				this.layout.settings.sidebar.body_type = 'sidebar-hover';
			} else if (val == 'horizontal')  {
				this.layout.settings.sidebar.type = 'horizontal_sidebar';
        this.layout.settings.sidebar.body_type = '';
      }
      setTimeout(()=> {
        const elmnt = document.getElementById("myDIV");
        this.menuWidth = elmnt.offsetWidth;
        this.menuWidth > window.innerWidth  ? (this.hideRightArrow = false,this.hideLeftArrowRTL = false) : (this.hideRightArrow = true,this.hideLeftArrowRTL = true)
      }, 500)
  },
  destroyed() {
    window.removeEventListener("resize", this.handleResize);
  },
  mounted() {
    this.menuItems.filter(items => {
      if (items.path === this.$route.path)
        this.$store.dispatch("menu/setActiveRoute", items);
      if (!items.children) return false;
      items.children.filter(subItems => {
        if (subItems.path === this.$route.path)
          this.$store.dispatch("menu/setActiveRoute", subItems);
        if (!subItems.children) return false;
        subItems.children.filter(subSubItems => {
          if (subSubItems.path === this.$route.path)
            this.$store.dispatch("menu/setActiveRoute", subSubItems);
        });
      });
    });
  },
  methods: {
    setNavActive(item) {
      this.$store.dispatch("menu/setNavActive", item);
    },
    handleResize() {
      this.width = window.innerWidth - 310;
    },
    scrollToRightRTL() {
      this.temp = this.width + this.margin;
      // Checking condition for remaing margin
      if (this.temp === 0) {
        this.margin = this.temp;
        this.hideRightArrowRTL = true;
      }
      // else
      else {
        this.margin += this.width;
        this.hideRightArrowRTL = false;
        this.hideLeftArrowRTL = false;
      }
    },
    scrollToLeftRTL() {
      // If Margin is reach between screen resolution
      console.log("this.margin", this.margin);
      if (this.margin <= -this.width) {
        this.margin += -this.width;
        this.hideLeftArrowRTL = true;
      }
      //Else
      else {
        this.margin += -this.width;
        this.hideRightArrowRTL = false;
      }
    },
    scrollToLeft() {
      console.log('left');

      // If Margin is reach between screen resolution
      if (this.margin >= -this.width) {
        this.margin = 0;
        this.hideLeftArrow = true;
      }
      //Else
      else {
        this.margin += this.width;
        this.hideRightArrow = false;
      }
    },
    scrollToRight() {
      this.temp = this.menuWidth + this.margin;
      // Checking condition for remaing margin
      if (this.temp < this.menuWidth) {
        this.margin = -this.temp;
        this.hideRightArrow = true;
      }
      // else
      else {
        this.margin += -this.width;
        this.hideLeftArrow = false;
      }
    }
  }
};
</script>
