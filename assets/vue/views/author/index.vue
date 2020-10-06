<template>
    <div>
        <mdb-edge-header color="teal darken-2">
            <div class="category-page-background"></div>
        </mdb-edge-header>
        <bird-container>
            <h2 class="pb-4">
                <mdb-icon fab icon="css3" class="text-danger mr-2" />
                <strong>Lists Authors</strong>
                <div style="display: flex;float: right">
                    <mdb-btn @click="goTo('/acceuil')" color="default">Acceuil</mdb-btn>
                    <mdb-btn @click="add" color="secondary">Add Author</mdb-btn>
                </div>
            </h2>
            <section>
                <el-table
                        :data="authors"
                        style="width: 100%;margin-bottom: 20px;"
                        row-key="id"
                        ref="authorTable"
                        border
                        default-expand-all
                        highlight-current-row
                        :row-class-name="tableRowClassName"
                        :row-style="tableRowStyle">
                    <el-table-column
                            prop="name"
                            label="Name">
                    </el-table-column>
                    <el-table-column
                            fixed="right"
                            label="Action"
                            width="120">
                        <template slot-scope="scope">
                            <el-button @click.native.prevent="edit(scope.$index, authors)"
                                       type="primary"
                                       data-toggle="modal"
                                       data-target="#modalCenterTable"
                                       size="small"
                                       icon="el-icon-edit"
                                       circle>
                            </el-button>
                            <el-button @click.native.prevent="remove(scope.$index, authors)"
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
                <el-form :model="formAuthor">
                    <el-form-item label="Name">
                        <el-input v-model="formAuthor.name"></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="dialogFormVisible = false">Cancel</el-button>
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
    import { required } from 'vuelidate/lib/validators'

    export default {
        name: "index",
        validations:{
            formAuthor:{
                name: {
                    required
                }
            }
        },
        components: {
            BirdContainer,
            mdbIcon,
            mdbEdgeHeader,
            mdbBtn
        },
        data: ()=> {
          return {
              activeIndex: undefined,
              dialogFormVisible: false,
              formAuthor: {},
              title: null,
              isAdd: false,
              author: {}
          }
        },
        created() {
            this.allAuthorAction({page: 1})
        },
        computed:{
            ...mapState({
                authors: (state) => state.author.authors,
                total: (state) => state.author.total,
                maxPagination: (state) => state.author.maxPagination
            })
        },
        methods:{
            ...mapActions('author',['allAuthorAction','updateAuthorAction','removeAuthorAction']),
            ...mapMutations('author',['setAuthor']),
            goTo(path){
                this.$router.push({path: path})
            },
            async handleCurrentChange(page) {
                await this.allAuthorAction({page: page})
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
                this.isAdd = true
                this.formAuthor = {}
                this.title = 'Add Author'
                this.dialogFormVisible = true
            },
            edit(index, authors){
                this.isAdd = false
                this.author = authors[index]
                this.formAuthor = authors[index]
                this.title = 'Edit Author'
                this.dialogFormVisible = true
            },
            remove(index, authors){
                this.$confirm('Do you want to delete this line ?', 'Deletion', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    this.removeAuthorAction({id: authors[index].id})
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
            async validate(){
                this.$v.$touch()
                if (this.$v.$invalid) {
                    this.$notify({
                        title: 'Warning',
                        message: 'Mandatory name field',
                        type: 'warning'
                    });
                    return false
                }
               await this.updateAuthorAction({id: this.isAdd ? 0 : this.author.id, name: this.formAuthor.name})
                this.dialogFormVisible = false
            }
        }
    }
</script>

<style scoped>

</style>