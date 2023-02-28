<template>
    <div>
        <section class="material-half-bg">
        <div class="cover"></div>
        </section>
        <section class="login-content">
        <div class="logo">
            <h1>Vali</h1>
        </div>
        <div class="row registration-form mb-5">
            <div class="col-8 offset-2">
                <div class="card">
                    <div class="card-body">
                        <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN UP</h3>
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
                                <label class="control-label">BIRTHDATE</label>
                                <Datepicker v-model="post.birthdate" :format="format"  placeholder="Birthdate" :change="setAge()"/>
                                <span class="errors-material" v-if="errors.birthdate">{{errors.birthdate[0]}}</span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">AGE</label>
                                <input class="form-control" readonly v-model="post.age" type="text" placeholder="Age">
                                <span class="errors-material" v-if="errors.age">{{errors.age[0]}}</span>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">BIRTH PLACE</label>
                                <input class="form-control" v-model="post.birth_place" type="text" placeholder="Birth Place">
                                <span class="errors-material" v-if="errors.birth_place">{{errors.birth_place[0]}}</span>
                            </div>
                        </div>
                         <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">CIVIL STATUS</label>
                                <select class="form-control" v-model="post.civil_status">
                                    <option :value="1">Single</option>
                                    <option :value="2">Married</option>
                                    <option :value="3">Legally Separated</option>
                                    <option :value="4">Annulled</option>
                                    <option :value="5">Widow/er</option>
                                </select>
                                <span class="errors-material" v-if="errors.civil_status">{{errors.civil_status[0]}}</span>
                            </div>
                             <div class="form-group col-md-6">
                                <label class="control-label">CITIZENSHIP</label>
                                <select class="form-control" v-model="post.citizenship">
                                    <option :value="1">Filipino</option>
                                    <option :value="2">Dual Citizen</option>
                                    <option :value="3">Foreign National</option>
                                </select>
                                <span class="errors-material" v-if="errors.citizenship">{{errors.citizenship[0]}}</span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">RELIGION</label>
                                <input class="form-control" type="text" v-model="post.religion" placeholder="Religion">
                                <span class="errors-material" v-if="errors.religion">{{errors.religion[0]}}</span>                                
                            </div>
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
                            <div class="form-group col-md-6">
                                <label class="control-label">EMAIL</label>
                                <input class="form-control" v-model="post.email"  type="text" placeholder="Email">
                                <span class="errors-material" v-if="errors.email">{{errors.email[0]}}</span>
                            </div>
                             <div class="form-group col-md-6">
                                <label class="control-label">STUDENT TYPE</label>
                                <select class="form-control" v-model="post.student_type">
                                    <option :value="1">JHS</option>
                                    <option :value="2">SHS</option>
                                </select>
                                <span class="errors-material" v-if="errors.student_type">{{errors.student_type[0]}}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <!-- <div class="col-md-6 offset-md-3"> -->
                                <div class="form-group col-md-6">
                                    <label class="control-label">USERNAME</label>
                                    <input class="form-control" v-model="post.username" type="text" placeholder="Username" autofocus>
                                    <span class="errors-material" v-if="errors.username">{{errors.username[0]}}</span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">PASSWORD</label>
                                    <input class="form-control" type="password" v-model="post.password" placeholder="Password" autofocus>
                                    <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">CONFIRM PASSWORD</label>
                                    <input class="form-control" v-model="post.password_confirmation" type="password" placeholder="Password Confirmation" autofocus>
                                    <span class="errors-material" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>
                                </div>
                            <!-- </div> -->
                        </div>
                        <hr>
                        <button type="button" @click="registration()" :disabled="btndis" class="btn btn-primary">{{btncap}}</button>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
</template>

<script>
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

export default {
    components:{
        Datepicker,
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
            post:{},
            errors:[],
            btncap:"Submit",
            btndis:false
        }
    },
    methods:{
        registration(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.btncap = "Submitting ...";
                this.btndis = true;
                this.$axios.post('api/auth', this.post).then(res=>{
                    this.btncap = "Submit";
                    this.btndis = false;
                    this.post = {};
                    this.$router.push({name:'home'});
                }).catch(err=>{
                    this.btncap = "Submit";
                    this.btndis = false;
                    this.errors = err.response.data.errors
                });
            });
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
        }
    }
}
</script>

<style>

</style>
