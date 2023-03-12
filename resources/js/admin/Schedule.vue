<template>
    <main class="app-content">
        <!-- <div class="app-title">
            <div>
            <h1><i class="fa fa-calendar-plus-o"></i> Schedule</h1>
            <p>Set up Schedule</p>
            </div>
        </div> -->
            <div class="row">
                <div class="col-md-12">
                    <div class="tile">
                        <h3 class="tile-title">Schedule</h3>
                        <div class="alert alert-danger p-0" v-if="errors.errs">
                            <div class="alert errors-material m-0">
                                <div v-if="errors.errs"><strong>*</strong>{{errors.errs[0]}}</div>
                            </div>
                        </div>
                        <div class="tile-body">
                            <form class="form-horizontal row">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Ademic Year</label>
                                        <div class="col-md-8 p-0">
                                            <input class="form-control form-control-sm" type="text" readonly :value="schoolYearDisplay(post.school_year_tx)" >
                                            <!-- <span class="errors-material" v-if="errors.school_year">{{errors.school_year[0]}}</span> -->
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Type</label>
                                        <div class="col-md-8 p-0">
                                            <select class="form-control form-control-sm" v-model="post.type" @change="typeFilter(post.type)">
                                                <option :value="1">JHS</option>
                                                <option :value="2">SHS</option>
                                            </select>
                                            <span class="errors-material" v-if="errors.type">{{errors.type[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row" v-if="senior">
                                        <label class="control-label col-md-3">Strand</label>
                                        <div class="col-md-8 p-0">
                                            <select class="form-control form-control-sm" v-model="post.strand">
                                                <option v-for="(ls, idx) in strands" :key="idx" :value="ls.id">{{ ls.strand_code}}</option>
                                            </select>
                                            <span class="errors-material" v-if="errors.strand">{{errors.strand[0]}}</span>
                                        </div>
                                    </div>
                        
                                    <div class="form-group row" >
                                        <label class="control-label col-md-3">Grade</label>
                                        <div class="col-md-3 p-0">
                                            <select class="form-control form-control-sm" v-model="post.grade" @change="gradeLevel(post.grade)">
                                                <option v-for="(ls,idx) in grades" :key="idx" :value="ls.val">{{ ls.val}}</option>
                                              
                                            </select>
                                            <span class="errors-material" v-if="errors.grade">{{errors.grade[0]}}</span>
                                        </div>
                                
                                        <label class="control-label col-md-2" v-if="senior">Term</label>
                                        <div class="col-md-3 p-0" v-if="senior">
                                            <select class="form-control form-control-sm" v-model="post.term">
                                                <option :value="1">1st</option>
                                                <option :value="2">2nd</option>
                                            </select>
                                            <span class="errors-material" v-if="errors.term">{{errors.term[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Section</label>
                                        <div class="col-md-8 p-0">
                                            <select class="form-control form-control-sm" v-model="post.section" @change="filterSection(post.section)">
                                                <option v-for="(ls,idx) in sections" :key="idx" :value="ls.id">{{ ls.section_name }}</option>
                                                
                                            </select>
                                            <span class="errors-material" v-if="errors.section">{{errors.section[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Adviser</label>
                                        <div class="col-md-8 p-0">
                                            <input class="form-control form-control-sm" readonly v-model="post.advise" type="text">
                                            <span class="errors-material" v-if="errors.adviser">{{errors.adviser[0]}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Subject Code</label>
                                        <div class="col-md-8 p-0">
                                            <select class="form-control form-control-sm" v-model="post.subject" @change="filterSubject(post.subject)">
                                                <option v-for="(ls,idx) in subjects" :key="idx"  :value="ls.id">{{ ls.subject_code }}</option>
                                            </select>
                                            <span class="errors-material" v-if="errors.subject">{{errors.subject[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Descriptive Title</label>
                                        <div class="col-md-8 p-0">
                                            <textarea class="form-control form-control-sm" readonly v-model="post.descriptive_title" rows="4"></textarea>
                                            <span class="errors-material" v-if="errors.descriptive_title">{{errors.descriptive_title[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Unit</label>
                                        <div class="col-md-8 p-0">
                                            <input class="form-control form-control-sm" readonly v-model="post.unit" type="text">
                                            <span class="errors-material" v-if="errors.unit">{{errors.unit[0]}}</span>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="control-label col-md-12"><strong>Day/s</strong></label>
                                        <div class="col-md-8 p-0">
                                            <div class="form-check-inline" v-for="(ls,idx) in days" :key="idx" >
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input"  v-model="post.day" :value="ls.val" >{{ ls.name }}
                                                </label>
                                            </div>
                                            <hr>
                                            <span class="errors-material" v-if="errors.day">{{errors.day[0]}}</span>
                                        </div>
                                    </div>
                                      
                                    <div class=" row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">From:</label>
                                            <Datepicker class="form-control-sm" v-model="post.t_from" :is-24="false" time-picker mode-height="120" />
                                            <span class="errors-material" v-if="errors.t_from">{{errors.t_from[0]}}</span>
                                        </div>
                                      
                                        <div class="form-group col-md-6">
                                            <label class="control-label">To: </label>
                                            <Datepicker class="form-control-sm" v-model="post.t_to" :is-24="false" time-picker mode-height="120" :change="timeCheck()" />
                                            <span class="errors-material" v-if="errors.t_to">{{errors.t_to[0]}}</span>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Teacher</label>
                                        <div class="col-md-8 p-0">
                                            <select class="form-control form-control-sm" v-model="post.teacher">
                                                <option v-for="(ls, idx) in teachers" :key="idx" :value="ls.id">{{ls.first_name+" "+ls.last_name}}</option>
                                            </select>
                                            <span class="errors-material" v-if="errors.teacher">{{errors.teacher[0]}}</span>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="tile-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-3">
                            <button class="btn btn-primary btn-sm" @click="saveSchedule()" type="button"><i class="fa fa-fw fa-lg fa-save"></i>{{btncap}}</button>
                            &nbsp;&nbsp;&nbsp;
                            <a class="btn btn-secondary btn-sm" href="#" @click="newS()" ><i class="fa fa-fw fa-lg fa-file-text-o"></i>New</a>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="tile">
                        <div class="tile-title-w-btn" >
                        </div>
                        <div class="tile-body">
                            <b>List of Schedule </b><br>
                            <data-table class="mt-2" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                                <tbody>
                                    <tr class="tr-shadow" v-for="(list, idx) in schedules" :key="idx">
                                        <td class="desc">{{ list.subjectd.subject_code }}</td>
                                        <td class="desc">{{ list.subjectd.descriptive_title }}</td>
                                        <td class="desc">{{ extractTime(list.t_from) +" - "+extractTime(list.t_to) }}</td>
                                        <td class="desc">{{ xtractDay(list.day) }}</td>
                                        <td class="desc">{{ list.grade }}</td>
                                        <td class="desc">{{ list.sectiond.section_name }}</td>
                                        <td class="desc">{{ list.teacherd.first_name+" "+list.teacherd.last_name }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" @click="editS(list)" title="Edit">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </button>    
                                            
                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" @click="deleteS(list)" title="Delete">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>    
                                            </div>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td colspan="8" v-show="!noData(schedules)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                </tbody>
                            </data-table>
                            <hr>
                            <div class="col-md-12">
                                <pagination :pagination="pagination"
                                    @prev="listOfSchedule(pagination.prevPageUrl)"
                                    @next="listOfSchedule(pagination.nextPageUrl)"
                                    v-show="noData(schedules)">
                                </pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade delete-confirm">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Delete</h6>
                        </div>
                        <div class="modal-body">
                            <h4>Do you want to delete <strong>{{ deldata.subjectd.descriptive_title }}</strong>?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="confirmDelete()" class="btn btn-danger" >Yes</button>  
                            <button type="button" @click="cancel()" class="btn btn-secondary" >No</button>  
                        </div>
                    </div>
                </div>
            </div>
    
        </main>
</template>

<script>
import { ref } from 'vue';
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
        const time = ref({ 
            hours: new Date().getHours(),
            minutes: new Date().getMinutes()
        });
        
        return {
            time,
        }
    },
    data(){
        
        let sortOrders = {};
        let columns =[
        {label:'Subject Code', name:null},
        {label:'Descriptive Title', name:null},
        {label:'Time', name:null},
        {label:'Day', name:null},
        {label:'Grade Level', name:null},
        {label:'Section', name:null},
        {label:'Teacher', name:null},
        {label:'Action', name:null},
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            schedules:[],
            errors:[],
            post:{
                day:[]
            },
            deldata:{
                subjectd:{}
            },
            teachers:[],
            strands:[],
            sections:[],
            subjects:[],
            grades:[],
            senior: true,
            btncap:"Save",
            days:[
                {'name':'M - Monday', 'val':1},
                {'name':'T - Tuesday', 'val':2},
                {'name':'W - Wednesday', 'val':3},
                {'name':'TH - Thursday', 'val':4},
                {'name':'F - Friday', 'val':5},
                {'name':'S - Saturday', 'val':6},
            ],
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
        deleteS(data){
            this.deldata = data;
            $('.delete-confirm').modal('show');
        },
        cancel(){
            $('.delete-confirm').modal('hide');
        },
        saveSchedule(){
         
            // let txt = this.xtractDay(this.post.day)
            this.post.days =  JSON.stringify(this.post.day);
            if(this.post.id > 0){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.$axios.put('api/schedule/'+this.post.id, this.post).then(res=>{
                        // this.post = {};
                        // this.newS();
                        this.btncap = "Save"
                        this.$emit('show',{'message':'Schedule has been modified!'});
                        this.errors = [];
                        this.listOfSchedule();
                        // this.academicYear() 
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.errors = err.response.data.errors
                    });
                });
            }else{
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.$axios.post('api/schedule', this.post).then(res=>{
                        // this.post = {};
                        // this.newS();
                        this.btncap = "Save"
                        this.$emit('show',{'message':'Schedule has been saved!'});
                        this.listOfSchedule();
                        // this.academicYear() 
                        this.errors = [];
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.errors = err.response.data.errors
                    });
                });
            }
          
        },
        listOfSchedule(urls='api/schedule'){
            this.tableData.type = this.post.type;
            this.tableData.strand = this.post.strand;
            this.tableData.term = this.post.term;
            this.tableData.grade = this.post.grade;
            this.tableData.section = this.post.section;
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(urls,{params:this.tableData}).then(res=>{
                    this.errors = [];
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.schedules = data.data.data;
                        this.configPagination(data.data);
                    }else{
                        this.not_found = true;
                    }
                
                }).catch(err=>{
                    this.errors = err.response.data.errors
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
                this.listOfSchedule();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        editS(data){
           
            this.gradeLevel(data.grade);
            this.academicYear();
            this.typeFilter(data.type);

            setTimeout(() => {
               this.filterSection(data.section);
               this.filterSubject(data.subject);
            }, 1500);
           
            this.post = data;
            if(data.day.length > 0){
                this.post.day = JSON.parse(data.day);                
            }
            this.post.t_from = this.objectTime(data.t_from);
            this.post.t_to = this.objectTime(data.t_to);
        },
        newS(){
            this.post = {
                 day:[]
            };
            this.errors = [];
            this.academicYear() 
            this.schedules = [];
        },
        academicYear(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/academic-year').then(res=>{
                    this.post.school_year = res.data.id;
                    this.post.school_year_tx = res.data.description;  
                });
            });
        },
        listStrand(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-strand').then(res=>{
                    this.strands = res.data;
                })
            });
        },
        typeFilter(num){
            this.post.advise = null;
            this.post.descriptive_title = null;
            this.post.unit = null;
            this.sections = null;
            this.subjects = null;
            if(num == 1){
                this.grades = [
                    {'val':7},
                    {'val':8},
                    {'val':9},
                    {'val':10},
                ];
                this.senior = false;
            }else{
                this.grades = [
                    {'val':11},
                    {'val':12},
                ];
                 this.senior = true;
            }
        },
        gradeLevel(num){
            this.post.advise = null;
            this.post.descriptive_title = null;
            this.post.unit = null;
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
        filterSection(num){
            this.post.advise = null;
            this.sections.forEach(val=>{
                if(val.id == num){
                      this.post.adviser = val.adviser;
                      this.post.advise = val.advise.first_name+ " "+val.advise.last_name;
                }
            })
            this.listOfSchedule();
        },
        filterSubject(num){
            this.subjects.forEach(val=>{
                if(num == val.id){
                    this.post.descriptive_title = val.descriptive_title;
                    this.post.unit = val.unit;
                }
            });
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
        listTeacher(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-teacher').then(res=>{ 
                    this.teachers = res.data;
                });
            });
        },
        objectTime(data){
            let prs = "1/1/2019 "+data;
            const time = ref({ 
                hours: new Date(prs).getHours(),
                minutes: new Date(prs).getMinutes(),
                seconds: new Date(prs).getSeconds()
            });

            return time;
        },
        timeCheck(){
        //     let from = new Date("1/1/2020 "+this.post.t_from).getTime();
        //     let to = new Date("1/1/2020 "+this.post.t_to).getTime();
        //     console.log(this.post.t_to > this.post.t_from ,from, to)
        //    if(to < from){
        //        this.post.t_to = null;
        //    }
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
        confirmDelete(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.delete('api/schedule/'+this.deldata.id).then(res=>{
                    this.$emit('show',{'message':'Schedule has been Deleted!'});
                    this.listOfSchedule();
                    $('.delete-confirm').modal('hide');
                });
            });
        },
        schoolYearDisplay(data){
            let num = Number(data) + 1;
            if(data != null){
                return data+ " - "+ num;
            }
           
        },

    },
    mounted() {
        // this.listOfSchedule();
        this.academicYear();
        this.listStrand();
        this.listTeacher();
    },
}
</script>

<style>

</style>
     