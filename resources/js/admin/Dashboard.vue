<template>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
                <!-- <p>A free and open source Bootstrap 4 admin template</p> -->
                </div>
                <!-- <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                </ul> -->
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3">
                <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                    <div class="info">
                    <h4>Students</h4>
                    <p><b>{{ students.length }}</b></p>
                    </div>
                </div>
                </div>
                <div class="col-md-6 col-lg-3">
                <div class="widget-small info coloured-icon"><i class="icon fa fa-calendar fa-3x"></i>
                    <div class="info">
                    <h4>School Year</h4>
                    <p><b>{{ schoolYearDisplay(schoolyear.description) }}</b></p>
                    </div>
                </div>
                </div>
                <div class="col-md-6 col-lg-3">
                <div class="widget-small warning coloured-icon"><i class="icon fa fa-user-plus fa-3x"></i>
                    <div class="info">
                    <h4>Enrolled</h4>
                    <p><b>{{ enrolled.length }}</b></p>
                    </div>
                </div>
                </div>
                <div class="col-md-6 col-lg-3">
                <div class="widget-small danger coloured-icon"><i class="icon fa fa-file-text fa-3x"></i>
                    <div class="info">
                    <h4>Sections</h4>
                    <p><b>{{sections.length }}</b></p>
                    </div>
                </div>
                </div>
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
            enrolled:[],
            sections:[],
            schoolyear:{}
        }
    },
    methods: {
        getStudent(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/get-student').then(res=>{
                    this.students = res.data;
                })
            });
        },
        getEnrolled(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/get-enrolled').then(res=>{
                    this.enrolled = res.data;
                })
            });
        },
        getSection(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/get-section').then(res=>{
                    this.sections = res.data;
                })
            });
        },
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
    },
    mounted() {
        this.getStudent();
        this.getEnrolled();
        this.getSection();
        this.getSchoolYear();
    },
}
</script>

<style>

</style>