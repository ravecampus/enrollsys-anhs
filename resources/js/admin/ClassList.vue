<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-list"></i> Class List</h1>
                <!-- <p>Set up Students</p> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                      
                    <div class="tile">
                        <div class="row">
                            
                            <div class="col-md-3">
                                <label>Grade</label>
                                <select class="form-control" placeholder="Section" v-model="tableData.grade" @click="gradeLevel(tableData.grade)">
                                    <option :value="7">7</option>
                                    <option :value="8">8</option>
                                    <option :value="9">9</option>
                                    <option :value="10">10</option>
                                    <option :value="11">11</option>
                                    <option :value="12">12</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Section</label>
                                <select class="form-control" placeholder="Section" v-model="tableData.section">
                                    <option v-for="(ls, idx) in sections" :key="idx" :value="ls.id">{{ ls.section_name }}</option>
                                </select>
                            </div>
                            <div class="col-md-3" v-if="senior">
                                <label>Term</label>
                                <div class="input-group">
                                    <select class="form-control" placeholder="Section" v-model="tableData.term">
                                       <option :value="1">1st Term</option>
                                       <option :value="2">2nd Term</option>
                                        
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label>Subject</label>
                                <div class="input-group">
                                    
                                    <select class="form-control" placeholder="Section" v-model="tableData.subject">
                                       <option v-for="(ls, idx) in subjects" :key="idx" :value="ls.id">{{ ls.subject_code }}</option> 
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" @click="loadClassList()" type="button">
                                            <i class="fa fa-filter"></i>
                                            {{ btnload }}
                                        </button>
                                     
                                    </div>
                                </div>
                            </div>
                          
                        </div>
                        <hr>
                            <li class="errors-material" v-if="errors.grade">{{errors.grade[0]}}</li>
                            <li class="errors-material" v-if="errors.section">{{errors.section[0]}}</li>
                            <li class="errors-material" v-if="errors.term">{{errors.term[0]}}</li>
                            <li class="errors-material" v-if="errors.subject">{{errors.subject[0]}}</li>
                        <div class="tile-body mt-5" v-if="schedule.id != undefined">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- <small>Student Information</small> -->
                                    <div class="mb-2 mt-3"> YEAR LEVEL: <strong> {{ schedule.grade }} </strong></div>
                                    <div class="mb-2"> SECTION: <strong> {{ schedule.sectiond.section_name }} </strong></div>
                                    <div class="mb-2"> TEACHER NAME: <strong> {{ schedule.teacherd.first_name }}, {{ schedule.teacherd.last_name}}</strong></div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="mb-2 mt-3"> SUBJECT: <strong> {{ schedule.subjectd.subject_code }} </strong></div>
                                    <div class="mb-2"> SCHOOL YEAR: <strong> {{ schoolYearDisplay(schedule.school_year.description) }} </strong></div>
                                    <div class="mb-2"> TERM: <strong> {{ extractTerm(schedule.term) }}</strong></div>
                                </div>
                            </div>    

                            <!-- <b>List of Student </b><br> -->
                            <!-- <router-link :to="{name:'studentsarchive'}">Archives</router-link> -->
                            <data-table class="mt-4" :columns="columns">
                                <tbody>
                                    <tr class="tr-shadow" v-for="(list, idx) in enrollsched" :key="idx" :class="list.status == 2 ? 'bg-dropped' : ''">
                                         <td>
                                            {{idx+1}}
                                        </td>
                                        <td><strong class="text-success">{{ list.student.lrn }}</strong></td>
                                        <td>{{ list.student.last_name }}</td>
                                        <td>{{ list.student.first_name }}</td>
                                        <td>{{ list.student.middle_name }}</td>            
                                    </tr>
                                    <tr> 
                                        <td colspan="4" v-show="!noData(enrollsched)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                </tbody>
                            </data-table>
                            <hr>
                        </div>
                        <div class="text-centr" v-if="schedule.id == undefined">
                            No Result Found!
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
            sections:[],
            subjects:[],
            enrollsched:[],
            schedule:{
                sectiond:{},
                teacherd:{},
                subjectd:{},
                school_year:{},
            },
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
        listOfClass(urls='api/class-list'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(urls,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        if(data.data != null){
                            this.schedule = data.data;
                            this.enrollsched = data.data.enrollsched;
                            this.errors = [];
                        }else{
                            this.schedule = {
                                sectiond:{},
                                teacherd:{},
                                subjectd:{},
                                school_year:{},
                            };
                            this.enrollsched = [];
                            this.errors = [];
                        }
                       
                    }
                }).catch(err=>{
                    this.errors = err.response.data.errors
                });
            });
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
        extractStatus(num){
            return num == 1 ? "Enrolled" : num == 2 ? "Dropped" :"";
        },
        gradeLevel(num){
            if(num >= 7 && num < 11){
                this.senior = false;
                this.tableData.senior = 1;
            }else if(num >= 11 ){
                this.senior = true;
                this.tableData.senior = 2;
            }
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
        loadClassList(){
            this.listOfClass();
        },
        extractTerm(num){
            return num == 1 ? "1st" : num == 2 ? "2nd" :"None";
        }
    },
    mounted() {
  
    },
}
</script>

<style>

</style>
     