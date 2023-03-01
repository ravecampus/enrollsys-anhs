<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-plus-o"></i> Users</h1>
                <p>Set up User</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="tile">
                        <h3 class="tile-title">User</h3>
                        <div class="tile-body">
                            <form class="form-horizontal">
                                <div class="form-group row">
                                    <label class="control-label col-md-3">Last Name</label>
                                    <div class="col-md-8 p-0">
                                        <input class="form-control" v-model="post.last_name" type="text" placeholder="Enter Last Name">
                                        <span class="errors-material" v-if="errors.last_name">{{errors.last_name[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3">First Name</label>
                                    <div class="col-md-8 p-0">
                                        <input class="form-control" v-model="post.first_name" type="text" placeholder="Enter First Name">
                                        <span class="errors-material" v-if="errors.first_name">{{errors.first_name[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3">Middle Name</label>
                                    <div class="col-md-8 p-0">
                                        <input class="form-control" v-model="post.middle_name" type="text" placeholder="Enter Middle Name">
                                        <span class="errors-material" v-if="errors.middle_name">{{errors.middle_name[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3">Role</label>
                                    <div class="col-md-8 p-0">
                                        <select class="form-control" v-model="post.role">
                                            <option :value="1">Teacher</option>
                                            <option :value="2">Administrator</option>
                                        </select>
                                        <span class="errors-material" v-if="errors.role">{{errors.role[0]}}</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label class="control-label col-md-3">Username</label>
                                    <div class="col-md-8 p-0">
                                        <input class="form-control" v-model="post.username" type="text" placeholder="Enter Username">
                                        <span class="errors-material" v-if="errors.username">{{errors.username[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="post.id == null">
                                    <label class="control-label col-md-3">Paswword</label>
                                    <div class="col-md-8 p-0">
                                        <input class="form-control" v-model="post.password" type="text" placeholder="Enter Password">
                                        <span class="errors-material" v-if="errors.password">{{errors.password[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="post.id == null">
                                    <label class="control-label col-md-3">Paswword Confirmation</label>
                                    <div class="col-md-8 p-0">
                                        <input class="form-control" v-model="post.password_confirmation" type="text" placeholder="Enter Password Confirmation">
                                        <span class="errors-material" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</span>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                        <div class="tile-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-3">
                            <button class="btn btn-primary" @click="saveUser()" type="button"><i class="fa fa-fw fa-lg fa-save"></i>{{btncap}}</button>
                            &nbsp;&nbsp;&nbsp;
                            <a class="btn btn-secondary" href="#" @click="newS()" ><i class="fa fa-fw fa-lg fa-file-text-o"></i>New</a>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="tile">
                        <!-- <div class="overlay" v-if="teachers.length == 0">
                            <div class="m-loader mr-4">
                                <svg class="m-circular" viewBox="25 25 50 50">
                                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/>
                                </svg>
                            </div>
                            <h3 class="l-text">Loading</h3>
                        </div> -->
                        <div class="tile-title-w-btn" >
                        <h3 class="title">All Items</h3>
                        <!-- <p><a class="btn btn-primary icon-btn" href=""><i class="fa fa-plus"></i>Add Item	</a></p> -->
                        </div>
                        <div class="tile-body">
                            <b>List of User </b><br>
                            <data-table class="mt-2" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                                <tbody>
                                    <tr class="tr-shadow" v-for="(list, idx) in users" :key="idx">
                                        <td class="desc">{{ list.last_name}}, {{ list.first_name}} {{list.middle_name}}</td>
                                        <td class="desc">{{ list.username }}</td>
                                        <td class="desc">{{ extractUser(list.role) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-warning btn-sm" data-toggle="tooltip" @click="editUser(list)" title="Edit">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </button>
                                                 <button class="btn btn-primary btn-sm" data-toggle="tooltip" @click="changePass(list)" title="Edit">
                                                    <i class="fa fa-lock"></i> Account
                                                </button>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td colspan="4" v-show="!noData(users)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                </tbody>
                            </data-table>
                            <hr>
                            <div class="col-md-12">
                                <pagination :pagination="pagination"
                                    @prev="listOfTeacher(pagination.prevPageUrl)"
                                    @next="listOfTeacher(pagination.nextPageUrl)"
                                    v-show="noData(users)">
                                </pagination>
                            </div>
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
                            <button type="button" @click="passwordChange()" class="btn btn-primary btn-block" >Save Changes</button>  
                        </div>
                    </div>
                </div>
            </div>
    
        </main>
</template>

<script>
import DataTable from '../table/DataTable';
import PaginationVue from '../table/Pagination';

export default {
    components:{
        dataTable:DataTable,
        pagination:PaginationVue
    },
    data(){
        
        let sortOrders = {};
        let columns =[
        {label:'Full Name', name:null},
        {label:'Username', name:null},
        {label:'Role', name:null},
        {label:'Action', name:null},
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            users:[],
            errors:[],
            post:{},
            pass:{},
            btncap:"Save",
            columns:columns,
            sortOrders:sortOrders,
            sortKey:'created_at',
            tableData:{
                draw:0,
                length:10,
                search:'',
                column:0,
                archive:0,
                dir:'desc',
                filter:1,
            },
            pagination:{
                lastPage:'',
                currentPage:'',
                total:'',
                lastPageUrl:'',
                nextPageurl:'',
                prevPageUrl:'',
                from:'',
                to:''
            },
        }
    },
    methods: {
        changePass(data){
            this.pass = data;
            $('.change-pass').modal('show');
        },
        saveUser(){
            if(this.post.id > 0){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.$axios.put('api/users/'+this.post.id, this.post).then(res=>{
                        this.post = {};
                        this.btncap = "Save"
                        this.$emit('show',{'message':'User has been modified!'});
                        this.listOfUser();
                        this.errors = [];
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.errors = err.response.data.errors
                    });
                });
            }else{
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.$axios.post('api/users', this.post).then(res=>{
                        this.post = {};
                        this.btncap = "Save"
                        this.$emit('show',{'message':'User has been saved!'});
                        this.listOfUser();
                        this.errors = [];
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.errors = err.response.data.errors
                    });
                });
            }
            
        },
        listOfUser(urls='api/users'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(urls,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.users = data.data.data;
                        this.configPagination(data.data);
                    }else{
                        this.not_found = true;
                    }
                
                }).catch(err=>{
                
                });
            });
        },
        configPagination(data){
            this.pagination.lastPage = data.last_page;
            this.pagination.currentPage = data.current_page;
            this.pagination.total = data.total;
            this.pagination.lastPageUrl = data.last_page_url;
            this.pagination.nextPageUrl = data.next_page_url;
            this.pagination.prevPageUrl = data.prev_page_url;
            this.pagination.from = data.from;
            this.pagination.to = data.to;
        },
        sortBy(key){
            if(key != null){
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, 'name', key);
                this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
                this.listOfUser();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        editUser(data){
            this.post = data;
        },
        newS(){
            this.post = {};
            this.errors = [];
        },
        extractUser(num){
            return num == 1 ? "Teacher" : num == 2 ? "Administrator" : "";
        },
        passwordChange(){
             this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.post('api/users-pass', this.pass).then(res=>{
                    this.pass = {};
                    this.$emit('show',{'message':'Password has been change!'});
                    this.listOfUser();
                    this.errors = [];
                    $('.change-pass').modal('hide');
                }).catch(err=>{
                    this.errors = err.response.data.errors
                });
            });
        }
    },
    mounted() {
        this.listOfUser();
    },
}
</script>

<style>

</style>
     