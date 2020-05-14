<template>
    <div>
        <nav-header></nav-header>
        <div class="container-fluid">
            <div class="row">
                <nav-sidebar/>
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                    <content-app/>
                </main>
            </div>
        </div>

    </div>
</template>

<script>
    import NavHeader from "./component/NavHeader";
    import NavSidebar from "./component/NavSidebar";
    import ContentApp from "../../components/ContentApp";
    export default {
        name: "index",
        components:{
            ContentApp,
            NavHeader,
            NavSidebar
        }
    }
</script>
<style lang="scss" scoped>
    @import "../../../../assets/css/dashboard.css";
</style>