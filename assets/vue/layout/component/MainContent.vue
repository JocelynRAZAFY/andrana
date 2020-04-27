<template>
    <div>
        <section class="content">
            <div class="container-fluid">
                <main-container></main-container>
            </div>
        </section>
    </div>
</template>

<script>
    import MainContainer from "./MainContainer";
    export default {
        name: "MainContent",
        components:{
            MainContainer
        }
    }
</script>

<style lang="scss" scoped>

</style>
