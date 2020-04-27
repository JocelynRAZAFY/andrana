<template>
    <div class="profile-page">
        <section class="section-profile-cover section-shaped my-0">
            <div class="shape shape-style-1 shape-primary shape-skew alpha-4">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </section>
        <section class="section section-skew">
            <div class="container">
                <card shadow class="card-profile mt--300" no-body>
                    <div class="px-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="#">
                                        <img v-lazy="img" class="rounded-circle">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-5">
                    </div>
                    <div class="mt-5 py-5 border-top">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <b-form  @submit.stop.prevent>
                                    <b-form-group
                                            id="input-email"
                                            label="Email address"
                                            label-for="input-1"
                                    >
                                        <b-input v-model="formLogin.email" type="email"></b-input>
                                        <b-form-invalid-feedback :state="$v.formLogin.email.required">
                                            Enter your email
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback :state="$v.formLogin.email.validEmail">
                                            Email not valid
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                    <b-form-group
                                            id="input-password"
                                            label="Password"
                                            label-for="input-1"
                                    >
                                        <b-form-input
                                                v-model="formLogin.password"
                                                type="password"
                                                required
                                        ></b-form-input>
                                        <b-form-invalid-feedback :state="$v.formLogin.password.required">
                                            Enter your password
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                    <b-button block
                                              @click="login()"
                                              v-loading.fullscreen.lock="loading"
                                              variant="outline-primary">Login</b-button>
                                </b-form>
                            </div>
                        </div>
                    </div>
                </card>

            </div>
        </section>
    </div>
</template>

<script>
    import {mapMutations,mapState,mapActions} from 'vuex'
    import { required, minLength, between } from 'vuelidate/lib/validators'
    export default {
        name: "index",
        components:{

        },
        computed:{
            ...mapState({
                token: (state) => state.user.token,
                loading: (state) => state.other.loading
            }),
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
                formLogin: {
                    email: 'rt1jocelyn@gmail.com',
                    password: '123'
                },
                img: "image/vuejsymf.jpeg"
            }
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
    @import "../../../../assets/vendor/nucleo/css/nucleo.css";
    @import "../../../../assets/vendor/font-awesome/css/font-awesome.css";
    @import "../../../../assets/scss/argon.scss";
    .rounded-circle{
        width: 150px;
        height: 150px;
        border-radius: 100%;
        border: 5px solid #FFF;
    }
    .box-card {
        margin: 10% auto;
    }
    .text {
        font-size: 14px;
    }

    .item {
        padding: 18px 0;
    }

    .box-card {
        width: 480px;
    }
</style>