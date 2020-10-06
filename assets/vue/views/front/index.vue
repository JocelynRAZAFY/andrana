<template>
    <div style="margin-top: -15px">
        <mdb-edge-header color="teal darken-2">
            <div class="category-page-background"></div>
        </mdb-edge-header>
        <bird-container>
            <h2 class="pb-4">
                <mdb-icon fab icon="css3" class="text-danger mr-2" />
                <strong>Listes Articles</strong>
                <div style="display: flex;float: right">
                    <mdb-btn @click="goTo('/author')" color="default">Author</mdb-btn>
                    <mdb-btn @click="goTo('/article')" color="default">Article</mdb-btn>
                </div>
            </h2>
            <detail :article="article" v-if="$route.params.id"></detail>
            <mdb-list-group v-else>
                <template v-for="article in articles">
                    <mdb-nav-item
                            class="list-group-item list-group-item-action"
                            @click="goToArticle(article)">
                        <h5 class="justify-content-between d-flex align-items-center">
                            {{article.title}}
                            <span style="font-size: 9px">Author: {{article.author.name}}</span>
                            <mdb-icon icon="angle-right"/>
                        </h5>
                    </mdb-nav-item>
                </template>
            </mdb-list-group>
            <div class="block" v-if="!$route.params.id">
                <el-pagination
                        background
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange"
                        :current-page.sync="activeIndex"
                        :page-size="maxPagination"
                        layout="total, prev, pager, next"
                        :total="total">
                </el-pagination>
            </div>
        </bird-container>
    </div>
</template>

<script>
    import { mdbIcon,
        mdbJumbotron, mdbListGroup, mdbNavItem,
        mdbEdgeHeader,mdbBtn } from 'mdbvue';
    import BirdContainer from "../../components/BirdContainer";
    import {mapActions, mapState} from "vuex";
    import Detail from "./components/Detail";
    export default {
        name: "index",
        components: {
            Detail,
            BirdContainer,
            mdbIcon,
            mdbJumbotron,
            mdbListGroup,
            mdbNavItem,
            mdbEdgeHeader,
            mdbBtn
        },
        data(){
            return {
                activeIndex: undefined,
                article: {}
            }
        },
        computed:{
            ...mapState({
                articles: (state) => state.article.articles,
                total: (state) => state.article.total,
                maxPagination: (state) => state.article.maxPagination
            })
        },
        created() {
            this.allArticleAction({page: 1})
        },
        methods:{
            ...mapActions('article',['allArticleAction']),
            goTo(path){
                this.$router.push({path: path})
            },
            handleSizeChange(val) {
                console.log(`${val} items per page`);
            },
            async handleCurrentChange(page) {
                await this.allArticleAction({page: page})
            },
            goToArticle(article){
                this.article = article
                this.$router.push({path: '/article/'+article.id})
            }
        }
    }
</script>

<style lang="scss" scoped>
    #home {
        margin-top: 5em;
    }

    .category-page-background {
        width: 100%;
        height: 100%;
        opacity: 0.1;
        background: url('https://mdbootstrap.com/wp-content/uploads/2016/11/mdb-pro-min-1.jpg') center;
        background-size: cover;
    }

    .example-components-list {
        padding-top: 20px;
    }

    .example-components-list li {
        padding: 10px;
        background-color: white;
        border-bottom: 1px solid #f7f7f7;
        transition: .3s;
    }

    .example-components-list h6 {
        padding: 20px 10px 5px 10px;
        color: grey;
    }

    .example-components-list li:hover {
        background-color: #fafafa;
    }

    .example-components-list i {
        float: right;
        padding-top: 3px;
    }

    .nav-link.navbar-link h5 {
        color: #212529;
    }
</style>