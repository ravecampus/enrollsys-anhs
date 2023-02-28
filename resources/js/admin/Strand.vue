<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-plus-o"></i> Strand</h1>
                <p>Set up Strand</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="tile">
                        <h3 class="tile-title">Strand</h3>
                        <div class="tile-body">
                            <form class="form-horizontal">
                                <div class="form-group row">
                                    <label class="control-label col-md-3">Strand Code</label>
                                    <div class="col-md-8 p-0">
                                        <input class="form-control" v-model="post.strand_code" type="text" placeholder="Enter Strand Code">
                                        <span class="errors-material" v-if="errors.strand_code">{{errors.strand_code[0]}}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3">Descriptive</label>
                                    <div class="col-md-8 p-0">
                                        <textarea class="form-control" v-model="post.descriptive" rows="4" placeholder="Enter Descriptive"></textarea>
                                        <span class="errors-material" v-if="errors.descriptive">{{errors.descriptive[0]}}</span>
                                    </div>
                                </div>

                                
                            </form>
                        </div>
                        <div class="tile-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-3">
                            <button class="btn btn-primary" @click="saveStrand()" type="button"><i class="fa fa-fw fa-lg fa-save"></i>{{btncap}}</button>
                            &nbsp;&nbsp;&nbsp;
                            <a class="btn btn-secondary" href="#" @click="newS()" ><i class="fa fa-fw fa-lg fa-file-text-o"></i>New</a>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="tile">
                        <!-- <div class="overlay" v-if="strands.length == 0">
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
                            <b>List of Strand </b><br>
                            <data-table class="mt-2" :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                                <tbody>
                                    <tr class="tr-shadow" v-for="(list, idx) in strands" :key="idx">
                                        <td class="desc">{{ list.strand_code}}</td>
                                        <td class="desc">{{ list.descriptive}}</td>
                                        <td>
                                            <div class="table-data-feature">
                                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" @click="editS(list)" title="Edit">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </button>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td colspan="4" v-show="!noData(strands)">
                                            No Result Found!
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                </tbody>
                            </data-table>
                            <hr>
                            <div class="col-md-12">
                                <pagination :pagination="pagination"
                                    @prev="listOfStrand(pagination.prevPageUrl)"
                                    @next="listOfStrand(pagination.nextPageUrl)"
                                    v-show="noData(strands)">
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
        {label:'Strand Code', name:'strand_code'},
        {label:'Descriptive', name:'descriptive'},
        {label:'Action', name:null},
        ];
        
        columns.forEach(column=>{
            sortOrders[column.name] = -1;
        });
        return{
            strands:[],
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
        saveStrand(){
            if(this.post.id > 0){
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.$axios.put('api/strand/'+this.post.id, this.post).then(res=>{
                        this.post = {};
                        this.btncap = "Save"
                        this.$emit('show',{'message':'Strand has been modified!'});
                         this.listOfStrand();
                        this.errors = [];
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.errors = err.response.data.errors
                    });
                });
            }else{
                this.$axios.get('sanctum/csrf-cookie').then(response=>{
                    this.btncap = "Saving..."
                    this.$axios.post('api/strand', this.post).then(res=>{
                        this.post = {};
                        this.btncap = "Save"
                        this.$emit('show',{'message':'Strand has been saved!'});
                         this.listOfStrand();
                        this.errors = [];
                    }).catch(err=>{
                        this.btncap = "Save"
                        this.errors = err.response.data.errors
                    });
                });
            }
           
        },
        listOfStrand(urls='api/strand'){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.tableData.draw ++;
                this.$axios.get(urls,{params:this.tableData}).then(res=>{
                let data = res.data;
                    if(this.tableData.draw == data.draw){
                        this.strands = data.data.data;
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
                this.listOfStrand();
            }
        },
        getIndex(array, key, value){
            return array.findIndex(i=>i[key] == value)
        },
        noData(data){
            return data == undefined ? true : (data.length > 0) ? true : false;
        },
        editS(data){
            this.post = data;
        },
        newS(){
            this.post = {};
            this.errors = [];
        }
    },
    mounted() {
        this.listOfStrand();
    },
}
</script>

<style>

</style>
     