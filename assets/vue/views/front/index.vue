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
                            <div class="col-lg-4 order-lg-3 text-lg-right align-self-lg-center">
                                <div class="card-profile-actions py-4 mt-lg-0">
                                    <router-link class="mr-4" to="/login">
                                        <base-button type="info" size="sm" class="mr-4">Connect</base-button>
                                    </router-link>

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
                </card>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: "index",
        data(){
            return {
                img: "image/vuejsymf.jpeg"
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
</style>