<template>
    <div>
        <el-form :model="formArticle">
            <el-form-item label="Title">
                <el-input v-model="formArticle.title"></el-input>
            </el-form-item>
            <el-form-item  label="Author">
                <el-select v-model="selectedAuthor" placeholder="Select">
                    <el-option
                            v-for="item in authors"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>
            <div class="col-12">
                <Vueditor ref="editor"></Vueditor>
            </div>
        </el-form>
    </div>
</template>

<script>
    import {mapActions, mapMutations, mapState} from "vuex";
    import {required} from "vuelidate/lib/validators";

    export default {
        name: "FormArticle",
        validations:{
            formArticle:{
                title: {
                    required
                }
            }
        },
        data: ()=> {
            return {
                formArticle: {},
                selectedAuthor: 0,
            }
        },
        mounted() {
            if(this.article != null){
                let editor = this.$refs['editor']
                editor.setContent(this.article.description)
                this.formArticle = this.article
                this.selectedAuthor = this.article.author.id
            }else {
                this.selectedAuthor = this.authors[0].id
            }
        },
        beforeDestroy() {
            let editor = this.$refs['editor']
            editor.setContent(null)
            this.setArticle(null)
        },
        computed:{
            ...mapState({
                authors: (state) => state.article.authors,
                article: (state) => state.article.article,
                isAdd: (state) => state.article.isAdd,
            })
        },
        methods:{
            ...mapActions('article',['updateArticleAction']),
            ...mapMutations('article',['setArticle','setIsAdd']),
            async saveArticle(){
                const author = this.authors.find(item => item.id == this.selectedAuthor)
                let editor = this.$refs['editor']
                if(editor.getContent() == ''){
                    this.$notify({
                        title: 'Warning',
                        message: 'Mandatory description field',
                        type: 'warning'
                    });
                    return false
                }
                this.$v.$touch()
                if (this.$v.$invalid) {
                    this.$notify({
                        title: 'Warning',
                        message: 'Mandatory field',
                        type: 'warning'
                    });
                    return false
                }

                const param  = {
                    id: this.isAdd ? 0 : this.article.id,
                    title: this.formArticle.title,
                    author: author,
                    description: editor.getContent(),
                }
                await this.updateArticleAction(param)
                this.$emit('dialog',false)
            }
        }
    }
</script>

<style lang="scss" scoped>
    .vueditor {
        height: 75vh;
        margin-top: 18px;
        width: 100%;
        margin-left: -23px;
        /*background-color: rgb(30,35,59);*/
        /*color: #FFFFFF;*/
    }
</style>