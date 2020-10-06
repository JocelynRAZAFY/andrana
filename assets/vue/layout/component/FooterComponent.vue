<template>
    <div>
        <mdb-footer v-if="$store.state.doc.docs" color="stylish-color">
            <p class="footer-copyright mb-0 py-3 text-center">
                &copy; {{ new Date().getFullYear() }} Copyright:
                <a
                        href="https://mdbootstrap.com/docs/vue/?utm_source=DemoApp&utm_medium=MDBVue"
                >MDBootstrap.com</a
                >
            </p>
        </mdb-footer>
    </div>
</template>

<script>
    import {
        mdbFooter
    } from "mdbvue";

    export default {
        name: "FooterComponent",
        components:{
            mdbFooter
        }
    }
</script>

<style scoped>

</style>