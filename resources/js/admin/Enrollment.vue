<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-plus-o"></i> Enrollment ({{ schoolYearDisplay(schoolyear.description) }})</h1>
                <!-- <p>Set up Students</p> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tile">
                      
                        <div class="tile-title-w-btn" >
                            <p><button type="button" @click="showModal()" class="btn btn-primary icon-btn" href=""><i class="fa fa-plus"></i>Add</button></p>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" v-model="tableData.search" @input="listOfStudent()" class="form-control" placeholder="Search... (Last name, First Name, Middle Name, LRN)">
                            </div>
                        </div>
                        <div class="tile-body">
                            <b>List of Student </b><br>
                            <!-- <router-link :to="{name:'studentsarchive'}">Archives</router-link> -->
                            <data-table class="mt-2" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                                <tbody>
                                    <tr class="tr-shadow" v-for="(list, idx) in students" :key="idx" :class="list.status == 2 ? 'bg-dropped' : ''">
                                         <td>
                                            
                                        </td>
                                        <td><strong class="text-success">{{ list.lrn }}</strong></td>
                                        <td>{{ list.last_name }}, {{ list.first_name }} {{ list.middle_name }}</td>
                                        <td>{{ schoolYearDisplay(list.sy.description) }}</td>
                                        <td>{{ list.term == 1 ? "1st" :list.term == 2 ? "2nd" : "" }}</td>
                                        <td>{{ extractStudentType(list.student_type) }}</td>
                                        <td>{{ list.strand == null ? " " : list.strand.strand_code }}</td>
                                        <td>{{ list.grade }}</td>
                                        <td>{{ list.sectiond.section_name }}</td>
                                        <td>{{ formatDate(list.created_at) }}</td>
                                        <td> <strong>
                                            {{ extractStatus(list.status) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-info btn-sm" v-if="list.status == 0" data-toggle="tooltip" @click="acceptEnroll(list)">
                                                Accept
                                                </button>
                                                <button class="btn btn-sm" v-if="list.status != 0" :class="list.status == 1 ? 'btn-warning' :'btn-primary'" data-toggle="tooltip" @click="dropModal(list)" >
                                                {{ list.status == 1 ? "Drop" : "Enroll" }}
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" @click="deleteEnroll(list)">
                                                Delete
                                                </button>
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" @click="viewEnroll(list)">
                                                View
                                                </button>
                                            </div>
                                        </td>
                                       
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
             <div class="modal fade enrolled">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Enrollment</h6>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <small>Student Information</small>
                                    <div class="mb-2 mt-3"> STUDENT ID: <strong> {{ user.lrn }} </strong></div>
                                    <div class="mb-2"> NAME: <strong> {{ user.last_name }}, {{ user.first_name }} {{ user.middle_name}} </strong></div>
                                    <div class="mb-2"> STUDENT TYPE: <strong> {{ extractStudentType(user.student_type) }} </strong></div>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <div class="row">
                                     
                                        <div class="col-md-3">
                                            <!-- <small>Enrollment Information</small> -->
                                            <div class="mb-2 mt-3"> GRADE LEVEL: 
                                                <select v-model="enroll.grade" class="form-control form-control-sm" @change="gradeLevel(enroll.grade)">
                                                    <option v-for="(ls, idx) in grades" :key="idx">{{ ls.val }}</option>
                                                </select>
                                                <span class="errors-material" v-if="errors.grade">{{errors.grade[0]}}</span>
                                            </div>
                                            <div class="mb-2"> SECTION: 
                                                <select v-model="enroll.section" class="form-control form-control-sm" @change="getSchedule()">
                                                    <option v-for="(ls, idx) in sections" :key="idx" :value="ls.id">{{ ls.section_name }}</option>
                                                </select>
                                                <span class="errors-material" v-if="errors.section">{{errors.section[0]}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3" v-if="senior">
                                            <div class="mb-2 mt-3"> STRAND: 
                                                <select class="form-control form-control-sm" v-model="enroll.strand"  @change="getSchedule()">
                                                    <option v-for="(ls,idx) in strands" :key="idx" :value="ls.id">{{ ls.strand_code }}</option>
                                                </select>
                                                <span class="errors-material" v-if="errors.strand">{{errors.strand[0]}}</span>
                                            </div>
                                            <div class="mb-2"> TERM: 
                                                <select class="form-control form-control-sm" v-model="enroll.term"  @change="getSchedule()">
                                                    <option :value="1">1st term</option>
                                                    <option :value="2">2nd term</option>
                                                </select>
                                                <span class="errors-material" v-if="errors.term">{{errors.term[0]}}</span>
                                            </div>
                                            
                                        </div>
                                    
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <small>Subject and Scheduled</small>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>SUBJECT CODE</th>
                                                        <th>DESCRIPTIVE TITLE</th>
                                                        <th>UNIT</th>
                                                        <th>TIME|DATE</th>
                                                        <th>TEACHER</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(ls,idx) in schedules" :key="idx">
                                                        <td>{{ ls.subjectd.subject_code }}</td>
                                                        <td>{{ ls.subjectd.descriptive_title }}</td>
                                                        <td>{{ ls.subjectd.unit }}</td>
                                                        <td>{{ extractTime(ls.t_from) +" - "+ extractTime(ls.t_to)}}
                                                            | {{ xtractDay(ls.day) }}
                                                            
                                                        </td>
                                                        <td>{{ls.teacherd.last_name}}, {{ ls.teacherd.first_name }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>


                               
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="enrollStudent()" :disabled="btndis" class="btn btn-primary">{{btnenroll}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade delete-enroll">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Delete</h6>
                        </div>
                        <div class="modal-body">
                            <h4>Do you want to delete <strong>{{ post.lrn }}</strong>?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="confirmDelete()" class="btn btn-danger" >Yes</button>  
                            <button type="button" @click="cancel()" class="btn btn-secondary" >No</button>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade drop-enroll">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>{{ this.post.status == 1 ? 'Drop' : 'Enroll'}}</h6>
                        </div>
                        <div class="modal-body">
                            <h4>Do you want to {{ this.post.status == 1 ? 'Drop': 'Enroll' }} <strong>{{ post.lrn }}</strong>?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="confirmDrop()" class="btn btn-danger" >Yes</button>  
                            <button type="button" @click="cancel()" class="btn btn-secondary" >No</button>  
                        </div>
                    </div>
                </div>
            </div>

              <div class="modal fade accept-enroll">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Accept</h6>
                        </div>
                        <div class="modal-body">
                            <h4>Do you want to accept <strong>{{ post.lrn }}</strong>?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="confirmAccept()" class="btn btn-info" >Yes</button>  
                            <button type="button" @click="cancel()" class="btn btn-secondary" >No</button>  
                        </div>
                    </div>
                </div>
            </div>

            
              <div class="modal fade list-student">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Students</h6>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <div class="form-group">
                                    <input type="text" v-model="tableUser.search" @input="listOfUser()" placeholder="Search..." class="form-control">
                                </div>
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>NAME</th>
                                            <th>STUDENT TYPE</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(ls,idx) in users" :key="idx">
                                            <td>
                                               <input type="radio" v-model="post_.user" :value="ls.id" :id="'user'+ls.id" @click="filterUser(ls)" class="form-control form-control-sm">
                                            </td>
                                            <td>{{ ls.first_name }} {{ ls.middle_name }} {{ ls.last_name }}</td>
                                            <td>{{ extractStudentType(ls.student_type) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" v-show="!noData(users)">
                                            No Result Found!
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="btn-group">
                                <button type="button" @click="nextButton()" class="btn btn-primary">Next</button>
                            </div>
                        </div>
                        <div class="modal-footer">
                          
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
        {label:'FULLNAME', name:null},
        {label:'AY', name:null},
        {label:'TERM', name:null},
        {label:'TYPE', name:null},
        {label:'STRAND', name:null},
        {label:'GRADE', name:null},
        {label:'SECTION', name:null},
        {label:'DATE', name:null},
        {label:'STATUS', name:null},
        {label:'ACTION', name:null},
       
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            schoolyear:{},
            students:[],
            errors:[],
            post:{},
            btncap:"Save",
            btnenroll:"Enroll",
            columns:columns,
            btndis:false,
            sortOrders:sortOrders,
            sortKey:'created_at',
            users:[],
            user:{},
            pass:{},
            enroll:{},
            post_:{},
            tableUser:{
                search:"",
                length: 5
            },
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
            strands:[],
            grades:[],
            sections:[],
            schedules:[],
            senior:false

        }
    },
    methods: {
        showModal(){
            this.post = {};
            $('.list-student').modal('show');
        },
        deleteEnroll(data){
            this.post = data;
            $('.delete-enroll').modal('show');
        },
        cancel(){
            $('.delete-enroll').modal('hide');
            $('.accept-enroll').modal('hide');
        },
        dropModal(data){
            this.post = data;
            $('.drop-enroll').modal('show');
        },
        enrollModal(data){
            this.post = data;
            $('.drop-enroll').modal('show');
        },
        acceptEnroll(data){
            this.post = data;
            $('.accept-enroll').modal('show');
        },
        confirmDelete(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.delete('api/enroll/'+this.post.id).then(res=>{
                    this.post = {};
                    this.$emit('show',{'message':'Enrollment has been moved to archive!'});
                    this.errors = [];
                    this.listOfStudent();
                    $('.delete-enroll').modal('hide');
                });
            });
        },

         confirmDrop(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                if(this.post.status == 1){
                    this.post.status = 2
                }else if(this.post.status == 2){
                    this.post.status = 1;
                }
                this.$axios.post('api/enroll-drop/', this.post).then(res=>{
                    this.post = {};
                    this.$emit('show',{'message':'Enrollment has been moved to archive!'});
                    this.errors = [];
                    this.listOfStudent();
                    $('.drop-enroll').modal('hide');
                });
            });
        },
        confirmDrop(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                if(this.post.status == 1){
                    this.post.status = 2
                }else if(this.post.status == 2){
                    this.post.status = 1;
                }
                this.$axios.post('api/enroll-drop/', this.post).then(res=>{
                    this.post = {};
                    this.$emit('show',{'message':'Enrollment has been moved to archive!'});
                    this.errors = [];
                    this.listOfStudent();
                    $('.drop-enroll').modal('hide');
                });
            });
        },
         confirmAccept(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.post('api/accept-enroll', this.post).then(res=>{
                    this.post = {};
                    this.$emit('show',{'message':'Enrollment has been accepted!'});
                    this.errors = [];
                    this.listOfStudent();
                    $('.accept-enroll').modal('hide');
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
        listOfStudent(urls='api/enroll'){
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
        schoolYearDisplay(data){
            let num = Number(data) + 1;
            if(data != null){
                return data+ " - "+ num;
            }
        },
        extractStatus(num){
            return num == 0 ? "Pending": num == 1 ? "Enrolled" : num == 2 ? "Dropped" :"";
        },
        getSchoolYear(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/get-active').then(res=>{
                    this.schoolyear = res.data;
                })
            });
        },
        viewEnroll(data){
            this.$router.push({name:'viewenrollment', params:{'id':data.id}});
        },
        getStrand(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-strand').then(res=>{
                    this.strands = res.data;
                })
            });
        },
        getSection(id){
             this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-section/'+id).then(res=>{
                    this.sections = res.data;
                })
            });
        },
        specifyGrade(num){
            if(num == 1){
                this.senior = false;
                this.grades = [
                    {'val':7},
                    {'val':8},
                    {'val':9},
                    {'val':10},
                    ];
            }else if(num == 2){
                this.senior = true;
                this.grades = [
                    {'val':11},
                    {'val':12},
                    ];
            }
           
        },
        listOfUser(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/student-list/',{params:this.tableUser}).then(res=>{
                    let data = res.data;
                    this.users = data.data.data;
                })
            });
        },
        filterUser(data){
            this.user = data;
            this.enroll.type = data.student_type;
            this.enroll.school_year = this.schoolyear.id;
        },
        nextButton(){
            this.specifyGrade(this.user.student_type);
            $('.list-student').modal('hide');
            $('.enrolled').modal('show');
        },
        gradeLevel(num){
            this.getSection(num);
        },
        getSchedule(urls='api/enroll-schedule'){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get(urls,{params:this.enroll}).then(res=>{
                    this.schedules = res.data;
                })
            });
        },
                extractTime(time){
          // Check correct time format and split into components
            time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

            if (time.length > 1) { // If time format correct
                time = time.slice (1);  // Remove full string match value
                time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
                time[0] = +time[0] % 12 || 12; // Adjust hours
            }
            return time.join (''); // return adjusted time or original string
        },
        xtractDay(data){
            let data_ = JSON.parse(data);
            let txt = "";
            data_.sort((a, b) => a - b);
            data_.forEach(val=>{
                if(val == 1){
                    txt += "M";
                }
                if(val==2){
                    txt += "T";
                }
                if(val==3){
                    txt += "W";
                }
                if(val==4){
                    txt += "TH";
                }
                if(val==5){
                    txt += "F";
                }
                if(val==6){
                    txt += "S";
                }
            });

            return txt;
        },

        enrollStudent(){
          
            this.post_ = this.enroll;
            this.post_.student_type = this.enroll.type;
            this.post_.id = this.user.id;
              console.log(this.post_);
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.btnenroll = "Proccessing..."
                // this.btndis = true;
                this.$axios.post('api/enroll-student', this.post_).then(res=>{
                   this.btnenroll = "Enroll";
                   this.$emit('show',{'message':'You are enrolled!'});
                   this.listOfStudent();
                //    this.btndis = false;
                   this.errors = [];
                   this.post_ = {};
                   this.enroll = {};
                   this.user = {};
                   $('.enrolled').modal('hide');
                }).catch(err=>{
                    this.btnenroll = "Enroll";
                    // this.btndis = false;
                    this.errors = err.response.data.errors
                })
            });

        },
    },
    mounted() {
        this.getStrand();
        // this.getSection();
        this.listOfStudent();
        this.getSchoolYear();
        this.listOfUser();
    },
}
</script>

<style>

</style>
     