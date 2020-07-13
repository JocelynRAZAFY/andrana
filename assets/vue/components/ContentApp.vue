<template>
    <div class="router-view">
        <transition name="fade"
                    mode="out-in">
            <router-view></router-view>
        </transition>
    </div>
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
