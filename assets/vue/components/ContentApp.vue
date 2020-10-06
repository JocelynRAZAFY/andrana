<template>
    <main :style="{ marginTop: $store.state.doc.docs ? '100px' : '0px' }">
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>
    </main>
</template>

<script>
    export default {
        name: "ContentApp",
        data(){
            return {
            }
        },
        methods:{

        }
    }
</script>

<style lang="scss">

</style>
