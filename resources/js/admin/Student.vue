<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-plus-o"></i> Students</h1>
                <!-- <p>Set up Students</p> -->
                </div>
            </div>
            <div class="row d-none d-print-block">
                <div class="col-md-12">
                    <div class="text-center">
                        <div class="">Republic of the Philippines</div>
                        <div class="">Department of Education</div>
                        <i class="mb-2">{{ school.region }}</i>
                        <h6 class="mt-2">{{ school.division }}</h6>
                        <h5 class="mt-2">{{ school.school_name }}</h5>
                        <i class="">{{ school.address }}</i>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>LRN #</th>
                                <th>LASTNAME</th>
                                <th>FIRSTNAME</th>
                                <th>MIDDLENAME</th>
                                <th>STUDENT TYPE</th>
                                <th>AGE</th>
                                <th>BIRTHDATE</th>
                                <th>BIRTH PLACE</th>
                                <th>GENDER</th>
                                <th>CIVIL STATUS</th>
                                <th>CITIZENSHIP</th>
                                <th>RELIGION</th>
                                <th>ADDRESS</th>
                                <th>CONTACT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="tr-shadow" v-for="(list, idx) in list_students" :key="idx">
                                <td><strong class="text-success">{{ list.lrn }}</strong></td>
                                <td class="desc">{{ list.last_name }}</td>
                                <td class="desc">{{ list.first_name }}</td>
                                <td class="desc">{{ list.middle_name }}</td>
                                <td class="desc">{{ extractStudentType(list.student_type) }}</td>
                                <td class="desc">{{ list.age }}</td>
                                <td class="desc">{{ formatDate(list.birthdate) }}</td>
                                <td class="desc">{{ list.birth_place }}</td>
                                <td class="desc">{{ extractGender(list.gender) }}</td>
                                <td class="desc">{{ extractCS(list.civil_status) }}</td>
                                <td class="desc">{{ extractCZ(list.citizenship) }}</td>
                                <td class="desc">{{ list.religion }}</td>
                                <td class="desc">{{ list.address}}</td>
                                <td class="desc">{{ list.contact}}</td>
                               
                            </tr>
                            <tr class="spacer"></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row d-print-none">
                <div class="col-md-12">
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
                            <p class="btn-group">
                                <button type="button" @click="showModal()" class="btn btn-primary icon-btn" href=""><i class="fa fa-plus"></i>Add</button>
                            <button type="button" @click="printData()" class="btn btn-outline-primary icon-btn"><i class="fa fa-print"></i>Print</button></p>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <input type="text" v-model="tableData.search" @input="listOfStudent()" class="form-control" placeholder="Search... (Last name, First Name, Middle Name, LRN)">
                            </div>
                        </div>
                        <div class="tile-body">
                            <b>List of Student </b><br>
                            <router-link :to="{name:'studentsarchive'}">Archives</router-link>
                            <data-table class="mt-2" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                                <tbody>
                                    <tr class="tr-shadow" v-for="(list, idx) in students" :key="idx">
                                         <td>
                                            <div class="btn-group">
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" @click="editStudent(list)" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" @click="archiveStudent(list)" title="Archive">
                                                <i class="fa fa-archive"></i>
                                                </button>
                                                <button class="btn btn-warning btn-sm" data-toggle="tooltip" @click="passwordStudent(list)" title="Archive">
                                                    <i class="fa fa-lock"></i>
                                                </button>
                                                <button class="btn btn-secondary btn-sm" data-toggle="tooltip" @click="viewStudent(list)" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td><strong class="text-success">{{ list.lrn }}</strong></td>
                                        <td class="desc">{{ list.last_name }}</td>
                                        <td class="desc">{{ list.first_name }}</td>
                                        <td class="desc">{{ list.middle_name }}</td>
                                        <td class="desc">{{ extractStudentType(list.student_type) }}</td>
                                        <td class="desc">{{ list.age }}</td>
                                        <td class="desc">{{ formatDate(list.birthdate) }}</td>
                                        <td class="desc">{{ list.birth_place }}</td>
                                        <td class="desc">{{ extractGender(list.gender) }}</td>
                                        <td class="desc">{{ extractCS(list.civil_status) }}</td>
                                        <td class="desc">{{ extractCZ(list.citizenship) }}</td>
                                        <td class="desc">{{ list.religion }}</td>
                                        <td class="desc">{{ list.address}}</td>
                                        <td class="desc">{{ list.contact}}</td>
                                       
                                    </tr>
                                    <tr> 
                                        <td colspan="4" v-show="!noData(students)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                </tbody>
                            </data-table>
                            <hr>
                            <div class="col-md-12">
                                <pagination :pagination="pagination"
                                    @prev="listOfStudent(pagination.prevPageUrl)"
                                    @next="listOfStudent(pagination.nextPageUrl)"
                                    v-show="noData(students)">
                                </pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="modal fade student">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Student Info</h6>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                     <div class="row">
                                        <div class="form-group col-md-4">
                                            <label class="control-label">LRN</label>
                                            <input class="form-control" v-model="post.lrn" type="text" placeholder="LRN" autofocus>
                                            <span class="errors-material" v-if="errors.lrn">{{errors.lrn[0]}}</span>
                                        </div>
                                     </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label class="control-label">LAST NAME</label>
                                            <input class="form-control" v-model="post.last_name" type="text" placeholder="Last name" autofocus>
                                            <span class="errors-material" v-if="errors.last_name">{{errors.last_name[0]}}</span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="control-label">FIRST NAME</label>
                                            <input class="form-control" v-model="post.first_name" type="text" placeholder="First name" >
                                            <span class="errors-material" v-if="errors.last_name">{{errors.last_name[0]}}</span>
                                        </div>
                                         <div class="form-group col-md-4">
                                            <label class="control-label">MIDDLE NAME</label>
                                            <input class="form-control" v-model="post.middle_name" type="text" placeholder="Middle name">
                                            <span class="errors-material" v-if="errors.middle_name">{{errors.middle_name[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                       
                                        <div class="form-group col-md-4">
                                            <label class="control-label">GENDER</label>
                                            <select class="form-control" v-model="post.gender">
                                                <option :value="1">Male</option>
                                                <option :value="2">Female</option>
                                            </select>
                                            <span class="errors-material" v-if="errors.gender">{{errors.gender[0]}}</span>
                                        </div>
                                
                                        <div class="form-group col-md-4">
                                            <label class="control-label">BIRTHDATE</label>
                                            <Datepicker v-model="post.birthdate" :format="format"  placeholder="Birthdate" :change="setAge()"/>
                                            <span class="errors-material" v-if="errors.birthdate">{{errors.birthdate[0]}}</span>
                                        </div>
                                        <div class="form-group col-md-4">
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
                                        <div class="form-group col-md-4">
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
                                        <div class="form-group col-md-4">
                                            <label class="control-label">CITIZENSHIP</label>
                                            <select class="form-control" v-model="post.citizenship">
                                                <option :value="1">Filipino</option>
                                                <option :value="2">Dual Citizen</option>
                                                <option :value="3">Foreign National</option>
                                            </select>
                                            <span class="errors-material" v-if="errors.citizenship">{{errors.citizenship[0]}}</span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="control-label">RELIGION</label>
                                            <input class="form-control" type="text" v-model="post.religion" placeholder="Religion">
                                            <span class="errors-material" v-if="errors.religion">{{errors.religion[0]}}</span>                                
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="control-label">CONTACT</label>
                                            <input class="form-control" v-model="post.contact" type="text" placeholder="Contact">
                                            <span class="errors-material" v-if="errors.contact">{{errors.contact[0]}}</span>
                                            
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label class="control-label">ADDRESS</label>
                                            <input class="form-control" v-model="post.address" type="text" placeholder="Address">
                                            <span class="errors-material" v-if="errors.address">{{errors.address[0]}}</span>
                                        </div>
                                        <div class="form-group col-md-4">
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
                            
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="saveStudent()" :disabled="btndis" class="btn btn-primary">{{btncap}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade archive-student">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Archive</h6>
                        </div>
                        <div class="modal-body">
                            <h4>Do you want to move on archive <strong>{{ post.lrn }}</strong>?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="confirmArchive()" class="btn btn-warning" >Yes</button>  
                            <button type="button" @click="cancel()" class="btn btn-secondary" >No</button>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade change-pass">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Change Password ( Name: {{ pass.first_name +" "+pass.last_name}} )</h6>
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
        
        let sortOrders = {};
        let columns =[
        {label:'', name:null},
        {label:'LRN #', name:null},
        {label:'LASTNAME', name:null},
        {label:'FIRSTNAME', name:null},
        {label:'MIDDLENAME', name:null},
        {label:'STUDENT TYPE', name:null},
        {label:'AGE', name:null},
        {label:'BIRTHDATE', name:null},
        {label:'BIRTH PLACE', name:null},
        {label:'GENDER', name:null},
        {label:'CIVIL STATUS', name:null},
        {label:'CITIZENSHIP', name:null},
        {label:'RELIGION', name:null},
        {label:'ADDRESS', name:null},
        {label:'CONTACT', name:null},
       
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            students:[],
            errors:[],
            post:{},
            btncap:"Save",
            columns:columns,
            btndis:false,
            sortOrders:sortOrders,
            sortKey:'created_at',
            list_students:[],
            pass:{},
            school:{},
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
        showModal(){
            this.post = {};
            $('.student').modal('show');
        },
        editStudent(data){
            this.post = data;
            $('.student').modal('show');
        },
        archiveStudent(data){
            this.post = data;
            $('.archive-student').modal('show')
        },
        cancel(){
            $('.archive-student').modal('hide')
        },
        confirmArchive(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.delete('api/student/'+this.post.id).then(res=>{
                    this.post = {};
                    this.$emit('show',{'message':'Student has been moved to archive!'});
                    this.errors = [];
                    this.listOfStudent();
                    $('.archive-student').modal('hide');
                });
            });
        },
        saveStudent(){
            if(this.post.id > 0){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.btndis = true;
                    this.$axios.put('api/student/'+this.post.id, this.post).then(res=>{
                        this.post = {};
                        this.btncap = "Save"
                        this.btndis = false;
                        this.$emit('show',{'message':'Student has been modified!'});
                        this.listOfStudent();
                        this.errors = [];
                        $('.student').modal('hide');
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.btndis = false;
                        this.errors = err.response.data.errors
                    });
                });
            }else{
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.btndis = true;
                    this.$axios.post('api/student', this.post).then(res=>{
                        this.post = {};
                        this.btncap = "Save"
                        this.btndis = false;
                        this.$emit('show',{'message':'Student has been saved!'});
                        this.listOfStudent();
                        this.errors = [];
                        $('.student').modal('hide');
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.btndis = false;
                        this.errors = err.response.data.errors
                    });
                });
            }
           
            
        },
        listOfStudent(urls='api/student'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(urls,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.students = data.data.data;
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
                this.listOfStudent();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        editTeacher(data){
            this.post = data;
        },
        newS(){
            this.post = {};
            this.errors = [];
        },
        extractGender(num){
            return num == 1 ? "Male" : "Female";
        },
        extractCS(num){
            return num == 1 ? "Single" : num == 2 ? "Married" : num == 3 ? "Legally Separated" : num == 4 ? "Annulled" : num == 5 ? "Widow/er" : "";
        },
        extractCZ(num){
            return num == 1 ? "Filipino" : num == 2 ? "Dual Citizen" : num == 3 ? "Foreign National" : "";
        },
        formatDate(da){
            let d = new Date(da);
            const day =("0" + d.getDate()).slice(-2);
            const month = ("0"+(d.getMonth()+1)).slice(-2);
            const year =  d.getFullYear();
            return  month+ "-" + day  + "-" + year;
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
        extractStudentType(num){
            return num == 1 ? "JHS" : num == 2 ? "SHS" : "";
        },
        passwordStudent(data){
            this.pass = data;
            $('.change-pass').modal('show');
        },
        passwordChange(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.post('api/users-pass', this.pass).then(res=>{
                    this.pass = {};
                    this.$emit('show',{'message':'Password has been change!'});
                    this.listOfStudent();
                    this.errors = [];
                    $('.change-pass').modal('hide');
                }).catch(err=>{
                    this.errors = err.response.data.errors
                });
            });
        },
        viewStudent(list){
            this.$router.push({name:'viewstudent', params:{'id':list.id}});
        },
        printData(){
            window.print();
        },
        listStudent(){
             this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-student').then(res=>{
                    this.list_students = res.data;
                }).catch(err=>{
                
                });
            });
        },
        getSchool(id){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/school/').then(res=>{
                    this.school = res.data;
                })
            });
        },
    },
    mounted() {
        this.listOfStudent();
        this.listStudent();
        this.getSchool();
    },
}
</script>

<style type="text/css" media="print">
  @page { size: landscape; }
</style>