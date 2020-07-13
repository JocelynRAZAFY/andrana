<template>
    <div>
        <header-component/>
        <content-app/>
        <footer-component/>
    </div>
</template>

<script>
    import ContentApp from "../../components/ContentApp";
    import HeaderComponent from "../component/HeaderComponent";
    import FooterComponent from "../component/FooterComponent";
    export default {
        name: "index",
        components:{
            FooterComponent,
            HeaderComponent,
            ContentApp
        }
    }
</script>

<style scoped>

</style>