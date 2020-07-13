<template>
    <div id="home">
        <!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
        <mdb-btn color="primary">Primary</mdb-btn>

        <!-- Default button -->
        <mdb-btn color="default">Default</mdb-btn>

        <!-- Secondary button -->
        <mdb-btn color="secondary">Secondary</mdb-btn>

        <!-- Indicates a successful or positive action -->
        <mdb-btn color="success">Success</mdb-btn>

        <!-- Contextual button for informational alert messages -->
        <mdb-btn color="info">Info</mdb-btn>

        <!-- Indicates caution should be taken with this action -->
        <mdb-btn color="warning">Warning</mdb-btn>

        <!-- Indicates a dangerous or potentially negative action -->
        <mdb-btn color="danger">Danger</mdb-btn>
    </div>
</template>

<script>
    import { mdbBtn } from 'mdbvue';
    export default {
        name: "index",
        components: {
            mdbBtn
        },
        data(){
            return {
                img: "image/vuejsymf.jpeg"
            }
        }
    }
</script>

<style lang="scss" scoped>
    #home {
        margin-top: 5em;
    }
</style>