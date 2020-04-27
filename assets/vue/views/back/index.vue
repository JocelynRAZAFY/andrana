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
                                        <img :src="img" class="rounded-circle">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 order-lg-3 text-lg-right align-self-lg-center">
                                <div class="card-profile-actions py-4 mt-lg-0">
                                    <base-button type="info" size="sm"
                                                 @click="logout()"
                                                 class="mr-4">Logout</base-button>
                                </div>
                            </div>
                            <div class="col-lg-4 order-lg-1">
                                <div class="card-profile-stats d-flex justify-content-center">
                                    <h3>
                                        Symfony 5 & Vuejs
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 py-5 border-top">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <b-form  @submit.stop.prevent>
                                    <b-form-group
                                            id="input-email"
                                            label="Pseudo"
                                            label-for="input-1"
                                    >
                                        <b-input v-model="form.username"></b-input>
                                    </b-form-group>

                                    <b-button block
                                              @click="send()"
                                              v-loading.fullscreen.lock="loading"
                                              variant="outline-primary">Send</b-button>
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
    import {mapMutations, mapActions, mapState} from "vuex";
    import {removeToken} from "../../plugins/auth";

    export default {
        name: "index",
        data(){
            return {
                img: "/image/vuejsymf.jpeg",
                form:{
                    username: null
                }
            }
        },
        computed:{
            ...mapState({
                loading: (state) => state.other.loading
            }),
        },
        methods:{
            ...mapMutations('other',['setLoading']),
            ...mapActions('user',['userUpdateAction']),
            async send(){
                this.setLoading(true)
                await this.userUpdateAction(this.form.username)
                this.setLoading(false)
            },
            async logout(){
                removeToken()
               // this.deleteAllCookies()
                window.location.href = '/'
            },
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
</style>