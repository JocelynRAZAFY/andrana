<template>
    <div id="login">
        <div class="container-login100" style="background-image: url('image/bg-01.jpg');">
            <div class="wrap-login100 p-l-55 p-r-55 p-t-80 p-b-30">
            <form-login/>
            </div>
        </div>
        <div id="dropDownSelect1"></div>
    </div>
</template>

<script>
    import {mapMutations,mapState,mapActions} from 'vuex'
    import { required, minLength, between } from 'vuelidate/lib/validators'
    import FormLogin from "./component/FormLogin";
    export default {
        name: "index",
        components:{
            FormLogin
        },
        data(){
            return {

            }
        },

    }
</script>

<style lang="scss" scoped>


</style>