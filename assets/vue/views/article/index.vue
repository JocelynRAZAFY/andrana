<template>
    <div>
        <mdb-edge-header color="teal darken-2">
            <div class="category-page-background"></div>
        </mdb-edge-header>
        <bird-container>
            <h2 class="pb-4">
                <mdb-icon fab icon="css3" class="text-danger mr-2" />
                <strong>Articles</strong>
                <div style="display: flex;float: right">
                    <mdb-btn @click="goTo('/acceuil')" color="default">Acceuil</mdb-btn>
                    <mdb-btn @click="add" color="secondary">Add Article</mdb-btn>
                </div>
            </h2>
            <section>
                <el-table
                        :data="articles"
                        style="width: 100%;margin-bottom: 20px;"
                        row-key="id"
                        ref="authorTable"
                        border
                        default-expand-all
                        highlight-current-row
                        :row-class-name="tableRowClassName"
                        :row-style="tableRowStyle">
                    <el-table-column
                            prop="title"
                            label="Title">
                    </el-table-column>
                    <el-table-column
                            prop="author.name"
                            label="Author">
                    </el-table-column>
                    <el-table-column
                            fixed="right"
                            label="Action"
                            width="120">
                        <template slot-scope="scope">
                            <el-button @click.native.prevent="edit(scope.$index, articles)"
                                       type="primary"
                                       data-toggle="modal"
                                       data-target="#modalCenterTable"
                                       size="small"
                                       icon="el-icon-edit"
                                       circle>
                            </el-button>
                            <el-button @click.native.prevent="remove(scope.$index, articles)"
                                       type="danger"
                                       size="small"
                                       icon="el-icon-delete"
                                       circle>
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </section>
            <el-dialog :title="title"
                       :visible.sync="dialogFormVisible">
                <form-article v-if="dialogFormVisible"
                              ref="article"
                              @dialog="dialog($event)"/>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="cancel">Cancel</el-button>
                    <el-button type="primary" @click="validate">Validate</el-button>
                </span>
            </el-dialog>
            <div class="block">
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
    import BirdContainer from "../../components/BirdContainer";
    import { mdbIcon, mdbEdgeHeader,mdbBtn } from 'mdbvue';
    import {mapState, mapActions, mapMutations} from 'vuex';
    import FormArticle from "./components/FormArticle";
    export default {
        name: "index",
        components: {
            FormArticle,
            BirdContainer,
            mdbIcon,
            mdbEdgeHeader,
            mdbBtn
        },
        data: ()=> {
            return {
                activeIndex: undefined,
                dialogFormVisible: false,
                title: null
            }
        },
        created() {
           this.allArticleAction({page: 1})
        },
        computed:{
            ...mapState({
                articles: (state) => state.article.articles,
                total: (state) => state.article.total,
                maxPagination: (state) => state.article.maxPagination
            })
        },
        methods:{
            ...mapActions('article',['allArticleAction','removeArticleAction']),
            ...mapMutations('article',['setArticle','setIsAdd']),
            goTo(path){
                this.$router.push({path: path})
            },
            async handleCurrentChange(page) {
                await this.allArticleAction({page: page})
            },
            tableRowClassName({row, rowIndex}) {
                if (row.launch_success == true) {
                    return 'success-row';
                } else if (row.launch_success == false) {
                    return 'warning-row';
                }
                return 'other-row';
            },
            tableRowStyle({ row, rowIndex }) {
                // return 'background-color: pink'
            },
            handleSizeChange(val) {
                console.log(`${val} items per page`);
            },
            add(){
                this.setArticle(null)
                this.setIsAdd(true)
                this.title = 'Add Article'
                this.dialogFormVisible = true
            },
            edit(index, articles){
                this.setArticle(articles[index])
                this.setIsAdd(false)
                this.title = 'Edit Article'
                this.dialogFormVisible = true
            },
            remove(index, articles){
                this.$confirm('Do you want to delete this line ?', 'Deletion', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    this.removeArticleAction({id: articles[index].id})
                    this.$message({
                        type: 'success',
                        message: 'Deletion completed'
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Deletion canceled'
                    });
                });
            },
            validate(){
                this.$refs.article.saveArticle()
            },
            dialog(value){
                this.dialogFormVisible = value
            },
            cancel(){
                this.setArticle(null)
                this.dialogFormVisible = false
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>