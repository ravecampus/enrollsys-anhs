<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-plus-o"></i> Profile</h1>
                <p>Modify profile</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>Profile</h3>
                            <button type="button" @click="showPassword()" class="btn btn-sm btn-primary"> Change Password </button>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label">LAST NAME</label>
                                    <input class="form-control" v-model="post.last_name" type="text" placeholder="Last name" autofocus>
                                    <span class="errors-material" v-if="errors.last_name">{{errors.last_name[0]}}</span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">FIRST NAME</label>
                                    <input class="form-control" v-model="post.first_name" type="text" placeholder="First name" >
                                    <span class="errors-material" v-if="errors.last_name">{{errors.last_name[0]}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label">MIDDLE NAME</label>
                                    <input class="form-control" v-model="post.middle_name" type="text" placeholder="Middle name">
                                    <span class="errors-material" v-if="errors.middle_name">{{errors.middle_name[0]}}</span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">GENDER</label>
                                    <select class="form-control" v-model="post.gender">
                                        <option :value="1">Male</option>
                                        <option :value="2">Female</option>
                                    </select>
                                    <span class="errors-material" v-if="errors.gender">{{errors.gender[0]}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label">CONTACT</label>
                                    <input class="form-control" v-model="post.contact" type="text" placeholder="Contact">
                                    <span class="errors-material" v-if="errors.contact">{{errors.contact[0]}}</span>
                                    
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="control-label">ADDRESS</label>
                                    <input class="form-control" v-model="post.address" type="text" placeholder="Address">
                                    <span class="errors-material" v-if="errors.address">{{errors.address[0]}}</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label">USERNAME</label>
                                    <input class="form-control" v-model="post.username" type="text" placeholder="Username" autofocus>
                                    <span class="errors-material" v-if="errors.username">{{errors.username[0]}}</span>
                                </div>
                                
                            </div>
                            <hr>
                            <button type="button" @click="saveProfile()" class="btn btn-primary">{{btncap}}</button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal fade change-pass">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Change Password</h6>
                        </div>
                        <div class="modal-body">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="password" v-model="pass.password" class="form-control" placeholder="New Password">
                                        <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" v-model="pass.password_confirmation" class="form-control" placeholder="Password Confirmation">
                                        <span class="errors-material" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="changePassword()" class="btn btn-primary btn-block" >Save</button>  
                        </div>
                    
                    
                    </div>
                </div>
            </div>
    
        </main>
</template>

<script>
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

import DataTable from '../table/DataTable';
import PaginationVue from '../table/Pagination';

export default {
    components:{
        Datepicker,
        dataTable:DataTable,
        pagination:PaginationVue
    },
    setup() {
        // In case of a range picker, you'll receive [Date, Date]
        const format = (d) => {
            const day =("0" + d.getDate()).slice(-2);
            const month = ("0"+(d.getMonth()+1)).slice(-2);
            const year =  d.getFullYear();

            return  month+ "-" + day  + "-" + year;
        }
        return {
            format,
        }
    },
    data(){
        return{
            sections:[],
            errors:[],
            post:{},
            pass:{}, 
            teachers:[],
            auth:false,
            btncap:"Save Changes",
      
        }
    },
    methods: {
        saveProfile(){
            if(this.post.id > 0){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.$axios.post('api/admin-profile/', this.post).then(res=>{
                        this.btncap = "Save Changes"
                        this.$emit('show',{'message':'Profile has been modified!'});
                        this.errors = [];
                        this.post = window.Laravel.user;
                    }).catch(err=>{
                        this.btncap = "Save Changes"
                        this.errors = err.response.data.errors
                    });
                });
            }
           
        },
        setAge(){
            if(this.post.birthdate != null){
                let cy = new Date().getFullYear();
                let by = new Date(this.post.birthdate).getFullYear();
                let age = cy - by;
                let res = isNaN(age) ? 0 : age;
                this.post.age = res;
            }else{
                this.post.age = 0;
            }
        },
        showPassword(){
            this.pass.id = this.post.id;
            $('.change-pass').modal('show');
        },
        changePassword(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    // this.btncap = "Saving..."
                    this.$axios.post('api/auth-password/',this.pass).then(res=>{
                        this.pass = { };
                        this.$emit('show',{'message':'Password has been changed!'});
                         $('.change-pass').modal('hide');
                        this.errors = [];
                        this.post = window.Laravel.user;
                    }).catch(err=>{
                        // this.btncap = "Save Changes"
                        this.errors = err.response.data.errors
                    });
                });
        }
   
    
    },
    mounted() {
        if(window.Laravel.isLoggedin){
            this.post = window.Laravel.user;
			this.auth = true;
        }
    },
}
</script>

<style>

</style>
     