<template>
     <main class="app-content">
        <div class="app-title">
            <div>
            <h1><i class="fa fa-gear"></i> School Setting</h1>
            <!-- <p>Set up Students</p> -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">School Setup</h3>
                    <div class="alert alert-danger p-0" v-if="errors.errs">
                        <div class="alert errors-material m-0">
                            <div v-if="errors.errs"><strong>*</strong>{{errors.errs[0]}}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="tile-body">
                        <div class="row" >
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>School Id</label>
                                    <input type="text" v-model="post.school_id" class="form-control" placeholder="School ID">
                                    <span class="errors-material" v-if="errors.school_id">{{errors.school_id[0]}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>School Name</label>
                                    <input type="text" v-model="post.school_name" class="form-control" placeholder="School Name">
                                    <span class="errors-material" v-if="errors.school_name">{{errors.school_name[0]}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Region</label>
                                    <input type="text" v-model="post.region" class="form-control" placeholder="Region">
                                    <span class="errors-material" v-if="errors.region">{{errors.region[0]}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Division</label>
                                    <input type="text" v-model="post.division" class="form-control" placeholder="Division">
                                    <span class="errors-material" v-if="errors.division">{{errors.division[0]}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" v-model="post.address" class="form-control" placeholder="Address">
                                    <span class="errors-material" v-if="errors.address">{{errors.address[0]}}</span>
                                </div>
                            </div>
                          
                        </div>
                        <hr>
                          <button type="button" class="btn btn-primary" @click="updateSchool()">{{btncap }}</button>
                    </div>
                </div>
                 
            </div>
        </div>
     </main>
</template>

<script>
export default {
    data(){
        return{
            user:{},
            post:{},
            btncap :"Update",
            errors:[],

        }
    },
    methods: {
        updateSchool(){
              this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Updating..."
                    this.$axios.post('api/school', this.post).then(res=>{
                        this.post = res.data;
                        this.btncap = "Update"
                        this.errors = [];
                    }).catch(err=>{
                        this.btncap = "Update"
                        this.errors = err.response.data.errors
                    });
            });
        },
        getSchool(urls='api/enroll-schedule'){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/school/1').then(res=>{
                   this.post = res.data;
                })
            });
        },
  

    },
    mounted() {
        let data = window.Laravel.user;
        this.getSchool();

    },
}
</script>

<style>

</style>
