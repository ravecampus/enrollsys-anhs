<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-plus-o"></i> JHS Grade</h1>
                <!-- <p>Set up Students</p> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tile">
                        <div class="tile-title-w-btn d-print-none" >
                            <h3 class="title">All Items</h3>
                            <!-- <p><button type="button" @click="showModal()" class="btn btn-primary icon-btn" href=""><i class="fa fa-plus"></i>Add</button></p> -->
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
                        </div>
                        <hr>
                        <div class="row d-print-none mb-3">
                            <!-- <div class="col-md-3">
                                <input type="text" v-model="tableData.search" @input="listOfStudent()" class="form-control" placeholder="Search... (Last name, First Name, Middle Name, LRN)">
                            </div> -->
                            <div class="col-md-3">
                                <label>Grade</label>
                                <select class="form-control" placeholder="Section" v-model="tableData.grade" @click="gradeLevel(tableData.grade)">
                                    <option :value="7">7</option>
                                    <option :value="8">8</option>
                                    <option :value="9">9</option>
                                    <option :value="10">10</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Section</label>
                                <select class="form-control" placeholder="Section" v-model="tableData.section">
                                    <option v-for="(ls, idx) in sections" :key="idx" :value="ls.id">{{ ls.section_name }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Subject</label>
                                <div class="input-group">
                                    <select class="form-control" placeholder="Section" v-model="tableData.subject">
                                       <option v-for="(ls, idx) in subjects" :key="idx" :value="ls.id">{{ ls.subject_code }}</option>
                                        
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" @click="loadGrade()" type="button">
                                            <i class="fa fa-filter"></i>
                                            {{ btnload }}
                                        </button>
                                         <button class="btn btn-outline-primary" @click="printGrade()" type="button">
                                            <i class="fa fa-prnt"></i>
                                            Print
                                        </button>
                                     
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-3">
                                <div>Filter</div>
                                <button type="button" @click="loadGrade()" class="btn btn-primary">Load</button>
                            </div> -->
                        </div>
                        <div class="tile-body">
                            <b>Junior High Grade </b><br>
                            <!-- <router-link :to="{name:'studentsarchive'}">Archives</router-link> -->
                            <data-table class="mt-2 table" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                                <tbody>
                                    <tr class="tr-shadow" v-for="(list, idx) in grades" :key="idx">
                                        
                                        <td><strong class="text-success">{{ list.studentd.lrn }}</strong></td>
                                        <td>{{ list.studentd.last_name }}, {{ list.studentd.first_name }} 
                                            <!-- {{ list.studentd.middle_name }} -->
                                            </td>
                                        <td>{{ list.subjectd.subject_code }}</td>
                                        <td>{{ list.subjectd.descriptive_title }}</td>
                                        <td>{{ list.subjectd.unit }}</td>
                                        <td>{{ list.first_grade }}</td>
                                        <td>{{ list.second_grade }}</td>
                                        <td>{{ list.third_grade }}</td>
                                        <td>{{ list.fourth_grade }}</td>
                                        <td>{{ list.average }}</td>
                                        <td>{{ extractRemark(list.remark) }}</td>
                                        <td>
                                            <div class="table-data-feature d-print-none">
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" @click="showModal(list)" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td colspan="12" v-show="!noData(grades)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                </tbody>
                            </data-table>
                            <div class="col-md-12 d-print-none">
                                <pagination :pagination="pagination"
                                    @prev="listOfGrade(pagination.prevPageUrl)"
                                    @next="listOfGrade(pagination.nextPageUrl)"
                                    v-show="noData(grades)">
                                </pagination>
                            </div>
                             <hr>
                            <div class="row d-none d-print-block">
                                <div class="col-md-12">
                                    Printed Date : {{ formatDate(new Date()) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="modal fade grade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Grade</h6>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label class="control-label">1st Grade</label>
                                            <input class="form-control" v-model="post.first_grade" type="number" placeholder="" @input="solveGrade()" autofocus>
                                            <span class="errors-material" v-if="errors.first_grade">{{errors.first_grade[0]}}</span>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="control-label">2nd Grade</label>
                                            <input class="form-control" v-model="post.second_grade" type="number" placeholder="" @input="solveGrade()">
                                            <span class="errors-material" v-if="errors.second_grade">{{errors.second_grade[0]}}</span>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="control-label">3rd Grade</label>
                                            <input class="form-control" v-model="post.third_grade" type="number" placeholder="" @input="solveGrade()">
                                            <span class="errors-material" v-if="errors.third_grade">{{errors.third_grade[0]}}</span>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="control-label">4th Grade</label>
                                            <input class="form-control" v-model="post.fourth_grade" type="number" placeholder="" @input="solveGrade()">
                                            <span class="errors-material" v-if="errors.fourth_grade">{{errors.fourth_grade[0]}}</span>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Average</label>
                                            <input class="form-control" v-model="post.average" type="number" readonly placeholder="">
                                            <span class="errors-material" v-if="errors.average">{{errors.average[0]}}</span>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Remark</label>
                                            <input class="form-control" :value="extractRemark(post.remark)" type="text" readonly placeholder="">
                                            <!-- <span class="errors-material" v-if="errors.average">{{errors.average[0]}}</span> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="saveGrade()" :disabled="btndis" class="btn btn-primary">{{btncap}}</button>
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
        {label:'LRN #', name:null},
        {label:'NAME', name:null},
        {label:'SUBJECT CODE', name:null},
        {label:'TITLE', name:null},
        {label:'UNITS', name:null},
        {label:'1ST GRADE', name:null},
        {label:'2ND GRADE', name:null},
        {label:'3RD GRADE', name:null},
        {label:'4TH GRADE', name:null},
        {label:'AVERAGE', name:null},
        {label:'REMARKS', name:null},
        {label:'', name:null},
       
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            school:{},
            grades:[],
            errors:[],
            sections:[],
            subjects:[],
            post:{},
            btncap:"Save",
            columns:columns,
            btndis:false,
            btnload:"Load",
            sortOrders:sortOrders,
            sortKey:'created_at',
            tableData:{
                draw:0,
                length:10,
                search:'',
                column:0,
                grade: 0,
                section: 0,
                subject: 0,
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
        loadGrade(){
            this.btnload = "Loading...";
            this.listOfGrade();
        },

        showModal(data){
            this.post = data;
            $('.grade').modal('show');
        },
       
        cancel(){
            $('.archive-student').modal('hide')
        },
        saveGrade(){
            if(this.post.id > 0){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.btndis = true;
                    this.$axios.put('api/jhs-grade/'+this.post.id, this.post).then(res=>{
                        // this.post = {};
                        this.btncap = "Save"
                        this.btndis = false;
                        this.$emit('show',{'message':'Grade has been modified!'});
                        this.errors = [];
                        this.listOfGrade();
                        $('.grade').modal('hide');
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.btndis = false;
                        this.errors = err.response.data.errors
                    });
                });
            }
           
        },
        listOfGrade(urls='api/jhs-grade'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(urls,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.grades = data.data.data;
                        this.btnload = "Load";
                        this.$emit('show',{'message':'Data has been load!'});
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
                this.listOfGrade();
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
        gradeLevel(num){
            this.getSection(num);
            this.getSubject(num);
        },
        getSection(id){
             this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-section/'+id).then(res=>{
                    this.sections = res.data;
                })
            });
        },
        getSubject(id){
             this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-subject/'+id).then(res=>{
                    this.subjects = res.data;
                })
            });
        },
        solveGrade(){
            let num1 = this.post.first_grade;
            let num2 = this.post.second_grade;
            let num3 = this.post.third_grade;
            let num4 = this.post.fourth_grade;
            let res = 0;
            if(num4 == 0 && num3 == 0 && num2 == 0){
                res = num1;
            }else if(num4 == 0 && num3 == 0){
                res = (num1 + num2) / 2;
            }else if(num4 == 0){
                res = (num1 + num2 + num3) / 3;
            }else{
                res = (num1 + num2 + num3 + num4) /4;
            }

            if(num1 == 0){
                this.post.second_grade = 0;
                this.post.third_grade = 0;
                this.post.fourth_grade = 0;
            }else if(num2 == 0){
                this.post.third_grade = 0;
                this.post.fourth_grade = 0;
            }else if(num3 == 0){
                  this.post.fourth_grade = 0;
            }

            this.post.average = res;

            if(res >= 75){
                this.post.remark = 2;
            }else if(res < 75){
                this.post.remark = 1;
            }
        },
        extractRemark(num){
            return num == 2 ? "Passed" : num == 1 ? "Failed" : "";
        },
        getSchool(id){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/school/').then(res=>{
                    this.school = res.data;
                })
            });
        },
        printGrade(){
            window.print();
        }
    },
    mounted() {
        this.getSchool();
    },
}
</script>

<style>

</style>
     