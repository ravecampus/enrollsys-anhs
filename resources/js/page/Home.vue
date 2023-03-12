<template>
    <div>
        <section class="material-half-bg">
        <div class="cover"></div>
        </section>
        <section class="login-content">
        <div class="logo">
            <h1>{{title}}</h1>
        </div>
        
        <div class="card login-card">
            <div class="card-header bg-white">
                <h4 class="text-center mb-0">{{school.school_name}}</h4>
                <div class="text-center mt-0">{{school.address}}</div>
            </div>
            <div class="card-body">
               
                <form class="login-form card-body">
                <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
                <div class="alert alert-danger p-0" v-if="errors.errs">
                    <div class="alert errors-material m-0">
                        <div v-if="errors.errs"><strong>*</strong>{{errors.errs[0]}}</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">USERNAME</label>
                    <input class="form-control" @keyup.enter="login" v-model="post.username" type="text" placeholder="Username" autofocus>
                    <span class="errors-material" v-if="errors.username">{{errors.username[0]}}</span>
                </div>
                <div class="form-group">
                    <label class="control-label">PASSWORD</label>
                    <input class="form-control" @keyup.enter="login"  v-model="post.password" type="password" placeholder="Password">
                    <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
                </div>
                <div class="form-group">
                    <div class="utility">
                    <div class="animated-checkbox">
                        <label>
                        <input type="checkbox"><span class="label-text">Stay Signed in</span>
                        </label>
                    </div>
                    <!-- <p class="semibold-text mb-2"><a href="#" data-toggle="flip">Forgot Password ?</a></p> -->
                      <p class="semibold-text mb-2"><router-link :to="{name:'register'}" data-toggle="flip">Not yet Register?</router-link></p>
                    </div>
                </div>
                <div class="form-group btn-container">
                    <button type="button" :disabled="btndis" @click="login()" class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>{{ btncap }}</button>
                </div>
                
                </form>
            <!-- <form class="forget-form" action="index.html">
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Forgot Password ?</h3>
            <div class="form-group">
                <label class="control-label">EMAIL</label>
                <input class="form-control" type="text" placeholder="Email">
            </div>
            <div class="form-group btn-container">
                <button class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>RESET</button>
            </div>
            <div class="form-group mt-3">
                <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
            </div>
            </form> -->
            </div>
        </div>
        </section>
    </div>
</template>

<script>
export default {
    data(){
        return{
            post:{},
            errors:[],
            btncap:'SIGN IN',
            btndis:false,
            title:'',
            school:{}
        }
    },
    methods:{
        login(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.btndis = true;
                this.btncap = "SIGNING..."
                this.$axios.post('api/auth-signin', this.post).then(res=>{
                    this.btndis = false;
                    let data = res.data;
                    if(data.role == 1 ||data.role == 2){
                        window.location.href="/admin";
                    }else{
                        window.location.href="/student";
                    }
                    this.btncap = "SIGN IN";
                }).catch(err=>{
                    this.btndis = false;
                    this.btncap = "SIGN IN";
                    this.errors = err.response.data.errors
                });
            });
        },
        getSchoolName(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/school').then(res=>{
                    this.school = res.data;
                });
            });
        }
    },
    mounted() {
        this.title = window.Title.app_name;
         if(window.Laravel.isLoggedin){
            let user = window.Laravel.user;
            this.auth = true;
            if(user.role == 1 || user.role == 2){
                this.$router.push({name:'dashboard'})
            }else {
               this.$router.push({name:'studenthome'})
			}
        }
        this.getSchoolName();
    },
}
</script>

<style>

</style>
