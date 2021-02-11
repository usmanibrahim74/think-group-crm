export default {
  data() {
    return {
      toastOptions:{
        theme: 'outline',
        position: "top-right",
        duration: 2000
      }


    }
  },
  methods:{
    $success(message){
      this.$toasted.show(message, {
        ...this.toastOptions,
        type: "success"
      });
    },
    $info(message){
      this.$toasted.show(message, {
        ...this.toastOptions,
        type: "info"
      });
    },
    $error(message){
      this.$toasted.show(message, {
        ...this.toastOptions,
        type: "error"
      });
    },
    $alert(message,type){
      this.$toasted.show(message, {
        ...this.toastOptions,
        type: type
      });
    }
  }
}
