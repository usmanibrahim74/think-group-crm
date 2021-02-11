<template>
    <!-- page-wrapper Start-->
    <div class="page-wrapper">
        <div class="container-fluid p-0">
            <!-- login page start-->
            <div class="authentication-main">
                <div class="row">
                    <div class="col-md-12">
                        <div class="auth-innerright">
                            <div class="authentication-box">
                                <div class="text-center"><img alt="" src="../assets/images/endless-logo.png"></div>
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <h4>LOGIN</h4>
                                            <h6>Enter your Username and Password </h6>
                                        </div>
                                        <form class="theme-form">
                                            <div class="form-group">
                                                <label class="col-form-label pt-0">Your Name</label>
                                                <input v-model="email" class="form-control" required="" type="email">
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Password</label>
                                                <input v-model="password" autocomplete="" class="form-control" required="" type="password">
                                            </div>
                                            <div class="checkbox p-0">
                                                <input id="checkbox1" type="checkbox">
                                                <label for="checkbox1">Remember me</label>
                                            </div>
                                            <div class="form-group form-row mt-3 mb-0">
                                                <button @click="signUp" class="btn btn-primary btn-block" type="button">Login</button>
                                            </div>
                                            <div class="form-group form-row mt-3 mb-0">
                                                <button @click="login" class="btn btn-secondary btn-block" type="button">Login With
                                                    Auth0
                                                </button>
                                            </div>
                                            <div class="login-divider"></div>
                                            <div class="social mt-3">
                                                <div class="form-group btn-showcase d-flex">
                                                    <button @click="socialLoginFacebook" class="btn social-btn btn-fb d-inline-block"><i
                                                            class="fa fa-facebook"></i></button>
                                                    <button @click="socialLogin"  class="btn social-btn btn-twitter d-inline-block"><i
                                                            class="fa fa-google"></i></button>
                                                    <button @click="socialLoginTwitter" class="btn social-btn btn-google d-inline-block"><i
                                                            class="fa fa-twitter"></i></button>
                                                    <button @click="socialLoginGit" class="btn social-btn btn-github d-inline-block"><i
                                                            class="fa fa-github"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- login page end-->
        </div>
    </div>
    <!-- latest jquery-->
</template>

<script>
import firebase from 'firebase';
import Userauth from '../auth/js/index'
export default {
    name: 'login',
    data() {
        return {
            email: 'test@admin.com',
            password: 'test@123456'
        }
    },
    methods: {
        signUp: function () {
            if (this.email == "" && this.password == '') {
                    this.email = "test@admin.com",
                    this.password = "test@123456"
            } else {
                firebase.auth().signInWithEmailAndPassword(this.email, this.password).then(
                    (result) => {
                        Userauth.localLogin(result)
                        this.$router.replace('/')
                    },
                    (err) => {
                        this.email = "test@admin.com",
                        this.password = "test@123456"
                        this.$toasted.show('Oops...' + err.message, {theme: 'bubble',   position: "bottom-right",   type: 'error', duration: 2000});
                    }
                );
            }
        },

        socialLogin() {
            const provider = new firebase.auth.GoogleAuthProvider();
            firebase.auth().signInWithPopup(provider).then((result) => {
                Userauth.localLogin(result)
                this.$router.replace('/');
            }).catch((err) => {
                alert('Oops. ' + err.message)
            });
        },
        socialLoginFacebook() {            
            const provider = new firebase.auth.FacebookAuthProvider();
            firebase.auth().signInWithPopup(provider).then((result) => {
                Userauth.localLogin(result)
                this.$router.replace('/');
            }).catch((err) => {
                alert('Oops. ' + err.message)
            });
        },
        socialLoginTwitter() {
            const provider = new firebase.auth.TwitterAuthProvider();
            firebase.auth().signInWithPopup(provider).then((result) => {
                Userauth.localLogin(result)
                this.$router.replace('/');
            }).catch((err) => {
                alert('Oops. ' + err.message)
            });
        },
        socialLoginGit() {
            const provider = new firebase.auth.GithubAuthProvider();
            firebase.auth().signInWithPopup(provider).then((result) => {
                Userauth.localLogin(result)
                this.$router.replace('/');
            }).catch((err) => {
                alert('Oops. ' + err.message)
            });
        },
        login () {
            Userauth.login()
        }
    }
}
</script>