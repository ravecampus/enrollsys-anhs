<template>
 <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text"></i> Transaction Logs</h1>
                <!-- <p>Set up Students</p> -->
                </div>
        </div>

        <div class="row d-flex justify-content-center mb-100">         
                <div class="col-lg-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="card-title m-b-0">Transaction Logs</h4>
                    </div>
                    <ul class="list-style-none">
                        <li class="d-flex no-block card-body border-top" v-for="(list,idx) in transaction" :key="idx">
                            <i class="fa fa-check-circle w-30px m-t-5"></i>
                            <div>
                                <a href="#" class="m-b-0 font-medium p-0" data-abc="true">{{ list.event }}</a>
                                <div><strong class="text-muted">{{ list.usercon.last_name }}, {{ list.first_name }} </strong>
                                </div>
                                <span class="text-muted">{{ list.data }} </span>
                            </div>
                            <div class="ml-auto">
                                <div class="tetx-right">
                                    <h5 class="text-muted m-b-0">{{ formatDate(list.created_at).day }}</h5>
                                    <span class="text-muted font-16">{{ formatDate(list.created_at).month }}</span>
                                    <div class="text-muted">
                                        <small>{{ formatDate(list.created_at).year }}</small>
                                    </div>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
                    
    
 </main>
    
</template>

<script>
export default {
    data(){
        return{
            transaction:[]
        }
    },
    methods:{
        listOfTransaction(){
            this.$axios.get('sanctum/csrf-cookie').then(res=>{
                this.$axios.get('api/transaction-log').then(res=>{
                    // console.log(res.data)
                    this.transaction = res.data.data;
                });
            });
        },
        formatDate(da){
            let d = new Date(da);
            const day = d.getDate();
            const month = d.getMonth() +1;
            const year =  d.getFullYear();
            return  {day:day, month:this.extractMonth(month), year:year};
        },
        extractMonth(mon){
            let ret = "";
            if(mon == 1){
                ret = "JAN";
            }else if(mon == 2){
                ret = "FEB";
            }else if(mon == 3){
                ret = "MAR";
            }else if(mon == 4){
                ret = "APR";
            }else if(mon == 5){
                ret = "MAY";
            }else if(mon == 6){
                ret = "JUN";
            }else if(mon == 7){
                ret = "JUL";
            }else if(mon == 8){
                ret = "AUG";
            }else if(mon == 9){
                ret = "SEP";
            }else if(mon == 10){
                ret = "OCT";
            }else if(mon == 11){
                ret = "NOV";
            }else if(mon == 12){
                ret = "DEC";
            }

            return ret;
        }
    },
    mounted(){
        this.listOfTransaction();
    }
}
</script>

<style>

</style>
