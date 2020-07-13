<template>
    <div id="app-page">
        <router-view></router-view>
    </div>
</template>

<script>
    import axios from "axios";
    import {mapMutations,mapState} from "vuex"
    import {getToken,removeToken} from "./plugins/auth";
    export default {
        name: "App",
        data(){
          return {
              connexion: null
          }
        },
        computed:{
            ...mapState({
                token: (state) => state.user.token
            })
        },
        created() {
            axios.interceptors.response.use(undefined, (err) => {
                return new Promise(() => {
                    console.log(err.response.status)
                    if (err.response.status === 401) {
                        const h = this.$createElement;
                        this.$notify.warning({
                            title: 'Login',
                            message: h('i', { style: 'color: teal' }, 'Email ou mot de passe incorrect'),
                            duration: 2000
                        });
                        this.setLoading(false)
                    }else {
                        window.location.href = '/';
                    }
                    throw err;
                });
            });

            this.setToken(getToken() ?? null)
            if(getToken()){
                console.log('app connected')
            }else {
            }
        },
        methods:{
            ...mapMutations('user',['setToken']),
            ...mapMutations('other',['setLoading'])
        },
        watch:{
            token(value){
                if(value){
                    // URL is a built-in JavaScript class to manipulate URLs
                    const url = new URL('https://andrana.com:1337/.well-known/mercure');
                    url.searchParams.append('topic', 'https://andrana.com/books/1');
                    const eventSource = new EventSource(url);
                    eventSource.onmessage = event => {
                        console.log(JSON.parse(event.data));
                    }
                }else {
                    removeToken()
                }
            },
            $route(value){

            }
        }
    }
</script>

<style scoped>
    @import url("https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap");
</style>