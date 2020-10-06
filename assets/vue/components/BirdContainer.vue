<template>
    <div>
        <mdb-container class="free-bird">
            <mdb-row>
                <mdb-col md="8" class="mx-auto">
                    <mdb-jumbotron class="pt-4">
                        <slot></slot>
                    </mdb-jumbotron>
                </mdb-col>
            </mdb-row>
        </mdb-container>
    </div>
</template>

<script>
    import { mdbContainer, mdbRow, mdbCol, mdbIcon, mdbJumbotron} from 'mdbvue';

    export default {
        name: "BirdContainer",
        components: {
            mdbContainer,
            mdbRow,
            mdbCol,
            mdbIcon,
            mdbJumbotron
        },
    }
</script>

<style scoped>

</style>