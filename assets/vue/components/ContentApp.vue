<template>
    <div class="router-view">
        <div class="router-content">
            <div class="content-area__content">
                <transition name="fade"
                            mode="out-in">
                    <router-view></router-view>
                </transition>
            </div>
        </div>
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
