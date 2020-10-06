<template>
    <mdb-card class="card-image">
        <div class="text-center py-5 px-4 my-5">
            <div>
                <h2 class="card-title h1-responsive pt-3 mb-5 font-bold">
                    <strong style="color: #000">{{article.title}}</strong>
                </h2>
                <p style="font-size: 12px;margin-bottom: 1em;color: #000">
                    <strong>{{article.author.name}}</strong>
                </p>
                <p v-html="article.description"></p>
                <mdb-btn @click="goTo" size="md" icon="clone">Acceuil</mdb-btn>
            </div>
        </div>
    </mdb-card>
</template>

<script>
    import { mdbCard, mdbBtn } from 'mdbvue';
    export default {
        name: "Detail",
        props:{
          article: {type: Object}
        },
        components: {
            mdbCard,
            mdbBtn
        },
        methods:{
            goTo(){
                this.$router.push({path: '/'})
            }
        }
    }
</script>

<style scoped>

</style>