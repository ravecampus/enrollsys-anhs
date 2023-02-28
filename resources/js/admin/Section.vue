<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-plus-o"></i> Section</h1>
                <p>Set up section</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="tile">
                        <h3 class="tile-title">Section</h3>
                        <div class="tile-body">
                        <form class="form-horizontal">
                             <div class="form-group row">
                                <label class="control-label col-md-3">Year Level</label>
                                <div class="col-md-8 p-0">
                                    <select class="form-control" placeholder="Section" v-model="post.year_level">
                                        <option :value="7">7</option>
                                        <option :value="8">8</option>
                                        <option :value="9">9</option>
                                        <option :value="10">10</option>
                                        <option :value="11">11</option>
                                        <option :value="12">12</option>
                                    </select>
                                    <span class="errors-material" v-if="errors.year_level">{{errors.year_level[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3">Section Name</label>
                                <div class="col-md-8 p-0">
                                    <input class="form-control" v-model="post.section_name" type="text" placeholder="Enter Section Name">
                                    <span class="errors-material" v-if="errors.section_name">{{errors.section_name[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3">Adviser</label>
                                <div class="col-md-8 p-0">
                                    <select class="form-control" v-model="post.adviser">
                                        <option v-for="(ls, idx) in teachers" :key="idx" :value="ls.id">{{ls.last_name }}, {{ ls.first_name }}</option>
                                    </select>
                                    <span class="errors-material" v-if="errors.adviser">{{errors.adviser[0]}}</span>
                                </div>
                            </div>
                        </form>
                        </div>
                        <div class="tile-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-3">
                            <button class="btn btn-primary" @click="saveSection()" type="button"><i class="fa fa-fw fa-lg fa-save"></i>{{btncap}}</button>
                            &nbsp;&nbsp;&nbsp;
                            <a class="btn btn-secondary" href="#" @click="newSec()" ><i class="fa fa-fw fa-lg fa-file-text-o"></i>New</a>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="tile">
                        <!-- <div class="overlay" v-if="sections.length == 0">
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
                            <b>List of Section </b><br>
                            <data-table class="mt-2" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                                <tbody>
                                    <tr class="tr-shadow" v-for="(list, idx) in sections" :key="idx">
                                        <td class="desc"><strong>{{ list.year_level}}</strong></td>
                                        <td class="desc">{{ list.section_name}}</td>
                                        <td class="desc">{{ list.advise.first_name+" "+list.advise.last_name}}</td>
                                        <td>
                                            <div class="table-data-feature">
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" @click="editSec(list)" title="Edit">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </button>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td colspan="4" v-show="!noData(sections)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                </tbody>
                            </data-table>
                            <hr>
                            <div class="col-md-12">
                                <pagination :pagination="pagination"
                                    @prev="listOfSection(pagination.prevPageUrl)"
                                    @next="listOfSection(pagination.nextPageUrl)"
                                    v-show="noData(sections)">
                                </pagination>
                            </div>
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
        {label:'Grade Level', name:null},
        {label:'Section Name', name:null},
        {label:'Adviser', name:null},
        {label:'Action', name:null},
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            sections:[],
            errors:[],
            post:{},
            teachers:[],
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
        saveSection(){
            if(this.post.id > 0){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.$axios.put('api/section/'+this.post.id, this.post).then(res=>{
                        this.post = {};
                        this.btncap = "Save"
                        this.$emit('show',{'message':'Section has been modified!'});
                         this.listOfSection();
                        this.errors = [];
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.errors = err.response.data.errors
                    });
                });
            }else{
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.$axios.post('api/section', this.post).then(res=>{
                        this.post = {};
                        this.btncap = "Save"
                        this.$emit('show',{'message':'Section has been saved!'});
                         this.listOfSection();
                        this.errors = [];
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.errors = err.response.data.errors
                    });
                });
            }
           
        },
        listOfSection(urls='api/section'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(urls,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.sections = data.data.data;
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
                this.listOfSection();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        editSec(data){
            this.post = data;
        },
        newSec(){
            this.post = {};
            this.errors = [];
        },
        listTeacher(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-teacher').then(res=>{ 
                    this.teachers = res.data;
                });
            });
        },

    },
    mounted() {
        this.listOfSection();
        this.listTeacher();
    },
}
</script>

<style>

</style>
     