<template>
     <main class="app-content">
        <div class="app-title">
            <div>
            <h1><i class="fa fa-user-plus"></i> My Scheduled</h1>
            <!-- <p>Set up Students</p> -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Schedule ({{ schoolYearDisplay(schoolyear.description) }})</h3>
                    <div class="alert alert-danger p-0" v-if="errors.errs">
                        <div class="alert errors-material m-0">
                            <div v-if="errors.errs"><strong>*</strong>{{errors.errs[0]}}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="tile-body" v-if="type == 1">
                        <div class="row" >
                            <div class="col-md-4">
                                <div class="mb-2"> STUDENT TYPE: <strong> {{ extractStudentType(enroll.student_type) }} </strong></div>
                                <div class="mb-2 mt-3"> GRADE LEVEL: <strong> {{ enroll.grade }} </strong></div>
                                <div class="mb-2"> SECTION: <strong> {{ section.section_name }}</strong></div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <!-- <small>Subject and Scheduled</small> -->
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>SUBJECT CODE</th>
                                            <th>DESCRIPTIVE TITLE</th>
                                            <th>UNIT</th>
                                            <th>TIME|DATE</th>
                                            <th>TEACHER</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(ls,idx) in schedules" :key="idx">
                                            <td>{{ ls.schedule.subjectd.subject_code }}</td>
                                            <td>{{ ls.schedule.subjectd.descriptive_title }}</td>
                                            <td>{{ ls.schedule.subjectd.unit }}</td>
                                            <td>{{ extractTime(ls.schedule.t_from) +" - "+ extractTime(ls.schedule.t_to)}}
                                                | {{ xtractDay(ls.schedule.day) }}
                                                
                                            </td>
                                            <td>{{ls.schedule.teacherd.last_name}}, {{ ls.schedule.teacherd.first_name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="tile-body"  v-if="type == 2">
                        <div class="row card card-body mb-2 shadow" v-for="(list, index) in enrolls" :key="index" >
                        <div class="col-md-12 row">
                            <div class="col-md-4">
                                <div class="mb-2"> STUDENT TYPE: <strong> {{ extractStudentType(list.student_type) }} </strong></div>
                                <div class="mb-2 mt-3"> GRADE LEVEL: <strong> {{ list.grade }} </strong></div>
                                <div class="mb-2"> SECTION: <strong> {{ list.sectiond.section_name }}</strong></div>
                            </div>
                             <div class="col-md-4">
                                <div class="mb-2"> STRAND: <strong> {{ list.strand.strand_code }} ({{ list.strand.descriptive }}) </strong></div>
                                <div class="mb-2 mt-3"> TERM: <strong> {{ extractTerm(list.term) }} </strong></div>
                            </div>
                          
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <!-- <small>Subject and Scheduled</small> -->
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>SUBJECT CODE</th>
                                            <th>DESCRIPTIVE TITLE</th>
                                            <th>UNIT</th>
                                            <th>TIME|DATE</th>
                                            <th>TEACHER</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(ls,idx) in list.ensched" :key="idx">
                                            <td>{{ ls.schedule.subjectd.subject_code }}</td>
                                            <td>{{ ls.schedule.subjectd.descriptive_title }}</td>
                                            <td>{{ ls.schedule.subjectd.unit }}</td>
                                            <td>{{ extractTime(ls.schedule.t_from) +" - "+ extractTime(ls.schedule.t_to)}}
                                                | {{ xtractDay(ls.schedule.day) }}
                                                
                                            </td>
                                            <td>{{ls.schedule.teacherd.last_name}}, {{ ls.schedule.teacherd.first_name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        </div>
                    </div>


                    <hr>
                </div>
            </div>
        </div>
     </main>
</template>

<script>
export default {
    data(){
        return{
            user:{},
            post:{},
            grades:[],
            strands:[],
            sections:[],

            section:{},
            enroll:{},
            enrolls:[],
            strand:{},
            type:0,

            senior:false,
            btndis:false,
            schoolyear:{},
            schedules:[],
            errors:[],
            btncap:"Enroll",
            filterData:{
                draw:0,
            }
        }
    },
    methods: {
        getAuthEnroll(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/enroll-auth').then(res=>{
                    let data = res.data;
                    this.type = data.type;
                    if(data.type == 1){
                        this.enroll = data.enr;
                        this.section = data.enr.sectiond;
                        this.schedules = data.enr.ensched;
                    }else{
                        this.enrolls = data.enr;
                    }
                });
            });
        },
        extractStudentType(num){
            return num == 1 ? "JHS" : num == 2 ? "SHS" : "";
        },
        getStrand(){
            this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-strand').then(res=>{
                    this.strands = res.data;
                })
            });
        },
        getSection(id){
             this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.$axios.get('api/list-section/'+id).then(res=>{
                    this.sections = res.data;
                })
            });
        },
        specifyGrade(num){
            if(num == 1){
                this.senior = false;
                this.grades = [
                    {'val':7},
                    {'val':8},
                    {'val':9},
                    {'val':10},
                    ];
            }else if(num == 2){
                this.senior = true;
                this.grades = [
                    {'val':11},
                    {'val':12},
                    ];
            }
           
        },
        gradeLevel(num){
            this.getSection(num);
        },
        getSchoolYear(){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/get-active').then(res=>{
                    this.schoolyear = res.data;
                    this.filterData.school_year = res.data.id;
                })
            });
        },
        schoolYearDisplay(data){
            let num = Number(data) + 1;
            if(data != null){
                return data+ " - "+ num;
            }
        },
        getSchedule(urls='api/enroll-schedule'){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get(urls,{params:this.filterData}).then(res=>{
                    // this.schedules = res.data;
                })
            });
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
        enrollData(){
            let data = window.Laravel.user;
            this.post = this.filterData;
            this.post.student_type = data.student_type;
            
             this.$axios.get('sanctum/csrf-cookie').then(response => {
                this.btncap = "Proccessing..."
                this.btndis = true;
                this.$axios.post('api/enroll', this.post).then(res=>{
                   this.btncap = "Enroll";
                   this.btndis = false;
                   this.errors = [];
                }).catch(err=>{
                    this.btncap = "Enroll";
                    this.btndis = false;
                    this.errors = err.response.data.errors
                })
            });

        },
        extractTerm(num){
            return num == 1 ? "1st" : num == 2 ? "2nd" :"";
        }

    },
    mounted() {
        // console.log(window.Laravel.user)
        let data = window.Laravel.user;
        this.specifyGrade(data.student_type);
        this.user = data;
        this.filterData.type = data.student_type;
        this.getStrand();
        this.getSchoolYear();
        this.getAuthEnroll();

    },
}
</script>

<style>

</style>
