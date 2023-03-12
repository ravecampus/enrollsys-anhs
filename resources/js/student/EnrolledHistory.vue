<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-history"></i> Enrollment History</h1>
                <!-- <p>Set up Students</p> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" v-if="post.id != undefined">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2 mt-3">LRN: <strong> {{ post.lrn }} </strong></div>
                                    <div class="mb-2"> NAME: <strong> {{ post.last_name }}, {{ post.first_name }} {{ post.middle_name }} </strong></div>
                                    <div class="mb-2"> GENDER: <strong> {{ extractGender(post.gender) }}</strong></div>
                                    <div class="mb-2 mt-3"> CONTACT: <strong> {{ post.contact }} </strong></div>
                                    <div class="mb-2"> ADDRESS: <strong> {{ post.address }} </strong></div>
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
                <div class="col-md-12" v-if="post.id != undefined">
                    <div class="card">
                        <div class="col-md-4 mt-3">    
                            SCHOOL YEAR: 
                            <div class="input-group">
                                <select class="form-control" v-model="post.sy">
                                    <option v-for="(ls,idx) in getUniq" :key="idx" :value="ls.id">{{ schoolYearDisplay(ls.description) }}</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" @click="filterYear()" type="button">
                                        <i class="fa fa-filter"></i>
                                        {{ btnload }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" v-if="view_senior">
                            <div class="row">
                                <div class="col-md-6">  
                                    <div class="mb-2 mt-3"> TERM: <strong> {{ extractTerm(data.term) }} </strong></div>
                                    <div class="mb-2"> GRADE: <strong> {{ data.grade }}</strong></div>
                                    <div class="mb-2 mt-3"> SECTION: <strong> {{ data.section }} </strong></div>
                                    <div class="mb-2"> ADVISER: <strong> {{ data.adviser }} </strong></div>
                                    <div class="mb-2"> STRAND: <strong> {{ data.strand }} </strong></div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>SUBJECT</th>
                                                    <th>1Q</th>
                                                    <th>2Q</th>
                                                    <th>FINAL</th>
                                                    <th>RATING</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(ls, idx) in grade" :key="idx">
                                                    <td>{{ ls.subjectd.descriptive_title }}</td>
                                                    <td>{{ ls.first_quarter }}</td>
                                                    <td>{{ ls.second_quarter }}</td>
                                                    <td>{{ ls.average }}</td>
                                                    <td>{{ extractRemark(ls.remark) }} </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2 mt-3"> TERM: <strong> {{ extractTerm(data2.term) }} </strong></div>
                                    <div class="mb-2"> GRADE: <strong> {{ data2.grade }}</strong></div>
                                    <div class="mb-2 mt-3"> SECTION: <strong> {{ data2.section }} </strong></div>
                                    <div class="mb-2"> ADVISER: <strong> {{ data2.adviser }} </strong></div>
                                    <div class="mb-2"> STRAND: <strong> {{ data2.strand }} </strong></div>
                                    <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>SUBJECT</th>
                                                        <th>1Q</th>
                                                        <th>2Q</th>
                                                        <th>FINAL</th>
                                                        <th>RATING</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(ls, idx) in grade2" :key="idx">
                                                        <td>{{ ls.subjectd.descriptive_title }}</td>
                                                        <td>{{ ls.first_quarter }}</td>
                                                        <td>{{ ls.second_quarter }}</td>
                                                        <td>{{ ls.average }}</td>
                                                        <td>{{ extractRemark(ls.remark) }} </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" v-if="!view_senior">
                            <div class="mb-2 mt-3"> GRADE: <strong> {{ data3.grade }}</strong></div>
                            <div class="mb-2 "> SECTION: <strong> {{ data3.section }} </strong></div>
                            <div class="mb-2"> ADVISER: <strong> {{ data3.adviser }} </strong></div>
                            <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SUBJECT</th>
                                                <th>1G</th>
                                                <th>2G</th>
                                                <th>3G</th>
                                                <th>4G</th>
                                                <th>FINAL</th>
                                                <th>RATING</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(ls, idx) in grade3" :key="idx">
                                                <td>{{ ls.subjectd.descriptive_title }}</td>
                                                <td>{{ ls.first_grade }}</td>
                                                <td>{{ ls.second_grade }}</td>
                                                <td>{{ ls.third_grade }}</td>
                                                <td>{{ ls.fourth_grade }}</td>
                                                <td>{{ ls.average }}</td>
                                                <td>{{ extractRemark(ls.remark) }} </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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
        {label:'#', name:null},
        {label:'LRN #', name:null},
        {label:'LASTNAME', name:null},
        {label:'FIRSTNAME', name:null},
        {label:'MIDDLENAME', name:null},
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            schoolyears:[],
            view_senior: true,
            post:{},
            user:{},
            sections:[],
            subjects:[],
            enrollsched:[],
            students:[],
            data:{},
            data2:{},
            data3:{},
            grade:[],
            grade2:[],
            grade3:[],
            senior: true,
            errors:[],
            post:{},
            btncap:"Save",
            btnload:"Load",
            columns:columns,
            btndis:false,
            sortOrders:sortOrders,
            sortKey:'created_at',
            pass:{},
            tableData:{
                draw:0,
                search:'',
                column:0,
                dir:'desc',
                length: 5,
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
    computed : {
        getUniq() {
            let ret = [];
            ret =  this.schoolyears.reduce((seed, current) => {
                return Object.assign(seed, {
                [current.id]: current
                });
            }, {});
            return _.orderBy(ret, 'description', 'desc');
        }
    },
    methods: {
        listOfStudent(url = 'api/student-list'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(url,{params:this.tableData}).then(res=>{
                    let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.students = data.data.data;
                        this.configPagination(data.data);
                    }
                
                })
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
        schoolYearDisplay(data){
            let num = Number(data) + 1;
            if(data != null){
                return data+ " - "+ num;
            }
        },
       
        extractTerm(num){
            return num == 1 ? "1st" : num == 2 ? "2nd" :"None";
        },
        viewUser(data){
            // this.post = {};
            // this.grade = [];
            // this.grade2 = [];
            // this.data = {};
            // this.data2 = {};
            this.post = data;
        },
        extractFData(data){
            data.forEach(val => {
                if(val.term != null){
                    this.view_senior = true;
                    this.data3 = {};
                    this.grade3 = [];
                    if(val.term == 1 && this.post.sy == val.school_year_id){
                        this.data.term = val.term;
                        this.data.grade = val.grade;
                        this.data.section = val.sectiond.section_name;
                        this.data.adviser = val.sectiond.advise.first_name+ " "+val.sectiond.advise.last_name;
                        this.data.strand = val.strand.strand_code;
                    }else if(val.term == 2 && this.post.sy == val.school_year_id){
                        this.data2.term = val.term;
                        this.data2.grade = val.grade;
                        this.data2.section = val.sectiond.section_name;
                        this.data2.adviser = val.sectiond.advise.first_name+ " "+val.sectiond.advise.last_name;
                        this.data2.strand = val.strand.strand_code;
                    }else{
                        this.data2 = {};
                        this.data = {};
                    }
                }else{
                    this.data = {}; 
                    this.data2 = {};
                    this.grade = [];
                    this.grade2 = [];
                    this.view_senior = false;

                    if(this.post.sy == val.school_year_id){
                        this.data3.grade = val.grade;
                        this.data3.section = val.sectiond.section_name;
                        this.data3.adviser = val.sectiond.advise.first_name+ " "+val.sectiond.advise.last_name;
                    }else{
                        this.data3 = {};
                    }
                  
                }
            });
        },
        extractGradeSenior(data){
                this.grade = [];
                this.grade2 = [];
            data.forEach(val=>{
    
                if(val.term == 1 && this.post.sy == val.school_year_id){
                    this.grade.push(val)
                }else if(val.term == 2 && this.post.sy == val.school_year_id){
                    this.grade2.push(val)
                }else{
                    this.grade2 = [];
                    this.grade = [];
                }
            });

        },
        extractGradeJunior(data){
            data.forEach(val=>{
                if(this.post.sy == val.school_year_id){
                    this.grade3.push(val)
                }else{
                      this.grade3 = [];
                }
            });

        },
        extractGender(num){
            return num == 1 ? "Male" : "Female";
        },
        // getSchoolYear(){
        //     this.$axios.get('sanctum/csrf-cookie').then(response=>{
        //         this.$axios.get('api/get-sy').then(res=>{
        //             this.schoolyears = res.data;
        //         })
        //     });
        // },
        filterYear(){
            console.log(this.post)
            this.extractFData(this.post.enroll);
            this.extractGradeSenior(this.post.shsgrade);
            this.extractGradeJunior(this.post.jhsgrade);
        },
        extractRemark(num){
            return num == 2 ? "Passed" : num == 1 ? "Failed" : "";
        },
        getAuthUser(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/auth-user').then(res=>{
                    this.viewUser(res.data);
                    this.user = res.data;
                })
            });
        },
        getAuthYear(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/auth-year').then(res=>{
                    this.schoolyears = res.data;
                    // console.log(res.data)
                })
            });
        }
    },
    mounted() {
        this.getAuthUser();
        // this.getSchoolYear()
        this.getAuthYear();

    },
}
</script>

<style>

</style>
     