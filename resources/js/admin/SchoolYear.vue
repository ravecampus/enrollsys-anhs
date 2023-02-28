<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-plus-o"></i> School Year</h1>
                <p>Set up scholl year</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="tile">
                        <h3 class="tile-title">School Year</h3>
                        <div class="tile-body">
                        <form class="form-horizontal">
                            <div class="form-group row">
                                <label class="control-label col-md-3">Description</label>
                                <div class="col-md-3 p-0">
                               
                                    <Datepicker v-model="post.description" year-picker mode-height="120" />
                                    <!-- <input class="form-control" v-model="post.description" type="text" placeholder="(2022-2023)"> -->
                                    <span class="errors-material" v-if="errors.description">{{errors.description[0]}}</span>
                                </div>
                                <div class="col-md-5">
                                    <p>Display : {{ schoolYearDisplay(this.post.description) }}</p>
                                </div>
                            </div>
                        </form>
                        </div>
                        <div class="tile-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-3">
                            <button class="btn btn-primary" @click="saveSchoolyear()" type="button"><i class="fa fa-fw fa-lg fa-save"></i>{{btncap}}</button>
                            &nbsp;&nbsp;&nbsp;
                            <a class="btn btn-secondary" href="#" @click="newSY()" ><i class="fa fa-fw fa-lg fa-file-text-o"></i>New</a>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="tile">
                        <!-- <div class="overlay" v-if="schoolyears.length == 0">
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
                            <b>List of School Year </b><br>
                            <data-table class="mt-2" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                                <tbody>
                                    <tr class="tr-shadow" v-for="(list, idx) in schoolyears" :key="idx">
                                        <td class="desc">{{ schoolYearDisplay(list.description) }}</td>
                                        <td>
                                            <div class="toggle">
                                                <label>
                                                    <input type="checkbox" :checked="chkShip(list)" @change="changeDefault(list)"><span class="button-indecator"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="table-data-feature">
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" @click="editSY(list)" title="Edit">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </button>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td colspan="4" v-show="!noData(schoolyears)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                </tbody>
                            </data-table>
                            <hr>
                            <div class="col-md-12">
                                <pagination :pagination="pagination"
                                    @prev="listOfSchoolYear(pagination.prevPageUrl)"
                                    @next="listOfSchoolYear(pagination.nextPageUrl)"
                                    v-show="noData(schoolyears)">
                                </pagination>
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
    data(){
        
        let sortOrders = {};
        let columns =[
        {label:'Description', name:'description'},
        {label:'Active', name:null},
        {label:'Action', name:null},
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            schoolyears:[],
            errors:[],
            post:{},
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
        saveSchoolyear(){
            if(this.post.id > 0){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.$axios.put('api/school-year/'+this.post.id, this.post).then(res=>{
                        this.post = {};
                        this.btncap = "Save"
                        this.$emit('show',{'message':'School Year has been modified!'});
                         this.listOfSchoolYear();
                        this.errors = [];
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.errors = err.response.data.errors
                    });
                });
            }else{
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.$axios.post('api/school-year', this.post).then(res=>{
                        this.post = {};
                        this.btncap = "Save"
                        this.$emit('show',{'message':'School Year has been saved!'});
                         this.listOfSchoolYear();
                        this.errors = [];
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.errors = err.response.data.errors
                    });
                });
            }
           
        },
        listOfSchoolYear(urls='api/school-year'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(urls,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.schoolyears = data.data.data;
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
                this.listOfSchoolYear();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        editSY(data){
            this.listOfSchoolYear();
            this.post = data;
        },
        newSY(){
            this.post = {};
            this.errors = [];
        },
        chkShip(data){
          return (data.is_active == 1) ? true : false;
        },
        changeDefault(data){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.post('api/set-active',data).then(res=>{
                  this.listOfSchoolYear();
                }).catch(err=>{

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
        this.listOfSchoolYear();
    },
}
</script>

<style>

</style>
     