<template>
    <div>
        <form class="login100-form validate-form">
				<span class="login100-form-title p-b-37">
					Sign In
				</span>

            <div class="wrap-input100 validate-input m-b-20" data-validate="Enter username or email">
                <input class="input100 is-invalid"
                       v-model="formLogin.email"
                       @blur="iniVar=false"
                       type="text"
                       name="username"
                       placeholder="username or email"
                       required>
                <span class="focus-input100"></span>
            </div>
            <mdb-alert color="danger" v-if="!$v.formLogin.email.required && !iniVar">
                Please enter your email
            </mdb-alert>
            <mdb-alert color="danger" v-if="!$v.formLogin.email.validEmail && !iniVar && formLogin.email != ''">
                Email invalid
            </mdb-alert>
            <div class="wrap-input100 validate-input m-b-25" data-validate = "Enter password">
                <input class="input100"
                       v-model="formLogin.password"
                       type="password"
                       name="pass"
                       placeholder="password"
                       required>
                <span class="focus-input100"></span>
            </div>
            <div class="container-login100-form-btn" v-loading="loading">
                <button class="login100-form-btn"
                        @click.prevent="login">
                    Sign In
                </button>
            </div>

            <div class="text-center p-t-57 p-b-20">
					<span class="txt1">
						Or login with
					</span>
            </div>

            <div class="flex-c p-b-112">
                <a href="#" class="login100-social-item">
                    <i class="fa fa-facebook-f"></i>
                </a>

                <a href="#" class="login100-social-item">
                    <img src="image/icons/icon-google.png" alt="GOOGLE">
                </a>
            </div>
            <div class="text-center">
                <a href="#" class="txt2 hov1">
                    Sign Up
                </a>
            </div>
        </form>
    </div>
</template>

<script>
    import { mdbAlert } from 'mdbvue';
    import {required} from "vuelidate/lib/validators";
    import {mapActions, mapMutations, mapState} from "vuex";
    export default {
        name: "FormLogin",
        components: {
            mdbAlert
        },
        validations:{
            formLogin:{
                email: {
                    required,
                    validEmail(value){
                        const reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
                        return reg.test(value)
                    }
                },
                password: {required}
            }
        },
        data(){
            return {
                formLogin: {},
                iniVar: true
            }
        },
        created() {
            // this.setLoading(true)
        },
        computed:{
            ...mapState({
                token: (state) => state.user.token,
                loading: (state) => state.other.loading
            }),
        },
        methods:{
            ...mapMutations('other',['setLoading']),
            ...mapActions('user',['loginAction']),
            async login(){
                this.$v.$touch()
                if (this.$v.$invalid) {
                    return
                }

                this.setLoading(true)
                await this.loginAction(this.formLogin)
                if(this.token != null){
                    await this.$router.push({ path: '/back', query: this.otherQuery })
                }
                this.setLoading(false)
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import "../../../../../assets/login/vendor/bootstrap/css/bootstrap.min.css";
    @import "../../../../../assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css";
    @import "../../../../../assets/login/fonts/iconic/css/material-design-iconic-font.min.css";
    @import "../../../../../assets/login/vendor/animate/animate.css";
    @import "../../../../../assets/login/vendor/css-hamburgers/hamburgers.min.css";
    @import "../../../../../assets/login/vendor/animsition/css/animsition.min.css";
    @import "../../../../../assets/login/vendor/select2/select2.min.css";
    @import "../../../../../assets/login/vendor/daterangepicker/daterangepicker.css";
    @import "../../../../../assets/login/css/util.css";
    @import "../../../../../assets/login/css/main.css";
</style>