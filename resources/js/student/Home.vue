<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                <div class="widget-small info coloured-icon"><i class="icon fa fa-calendar fa-3x"></i>
                    <div class="info">
                    <h4>School Year</h4>
                    <p><b>{{ schoolYearDisplay(schoolyear.description) }}</b></p>
                    </div>
                </div>
                </div>
                <div class="col-md-6 col-lg-4">
                <div class="widget-small primary coloured-icon"><i class="icon fa fa-user fa-3x"></i>
                    <div class="info">
                    <h4>Grade</h4>
                    <p v-if="enroll != null"><b>{{ enroll.grade }}</b></p>
                    </div>
                </div>
                </div>
           
                <div class="col-md-6 col-lg-4">
                <div class="widget-small warning coloured-icon"><i class="icon fa fa-address-card fa-3x"></i>
                    <div class="info">
                    <h4>Section</h4>
                    <p v-if="enroll != null"><b> {{section.section_name}}</b></p>
                    </div>
                </div>
                </div>
                <!-- <div class="col-md-6 col-lg-3">
                <div class="widget-small danger coloured-icon"><i class="icon fa fa-file-text fa-3x"></i>
                    <div class="info">
                    <h4>Sections</h4>
                    <p><b>{{sections.length }}</b></p>
                    </div>
                </div>
                </div> -->
            </div>
            <!-- <div class="row">
                <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">Monthly Sales</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
                    </div>
                </div>
                </div>
                <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">Support Requests</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="pieChartDemo"></canvas>
                    </div>
                </div>
                </div>
            </div> -->
        </main>
</template>

<script>
export default {
    data(){
        return{
            students:[],
            enroll:{},
            enrolls:{},
            section:{},
            schoolyear:{}
        }
    },
    methods: {
        getSchoolYear(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/get-active').then(res=>{
                    this.schoolyear = res.data;
                })
            });
        },
        schoolYearDisplay(data){
            let num = Number(data) + 1;
            if(data != null){
                return data+ " - "+ num;
            }
        },
        getAuthEnroll(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/enroll-auth').then(res=>{
                    let data = res.data;
                    this.type = data.type;
                    if(data.enr != null){
                        if(data.type == 1){
                            this.enroll = data.enr;
                            this.section = data.enr.sectiond;
                        }else{
                            this.enroll = data.enr[0];
                            this.section = data.enr[0].sectiond;
                        }
                    }
                    
                });
            });
        },
    },
    mounted() {
        let data = window.Laravel.user;
        this.getAuthEnroll();
        this.getSchoolYear();
    },
}
</script>

<style>

</style>