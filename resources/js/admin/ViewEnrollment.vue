<template>
     <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-plus-o"></i> Students</h1>
            </div>
        </div>
      
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center d-none d-print-block">
                            <div class="">Republic of the Philippines</div>
                            <div class="">Department of Education</div>
                            <i class="mb-2">{{ school.region }}</i>
                            <h6 class="mt-2">{{ school.division }}</h6>
                            <h5 class="mt-2">{{ school.school_name }}</h5>
                            <i class="">{{ school.address }}</i>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            Enrollment Information:
                            <button type="button" @click="printReport()" class="btn btn-primary btn-sm d-print-none">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                      
                        <hr>
                    </div>
                    <div class="col-md-6 col-print-6">
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                LRN # :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ enroll.lrn }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                NAME :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ enroll.last_name }}, {{ enroll.first_name }} {{ enroll.middle_name }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                GRADE :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ enroll.grade}}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                SECTION :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ enroll.sectiond.section_name }}
                            </div>
                        </div>
                       
                    </div>
             
                    <div class="col-md-6 col-print-6">
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                SCHOOL YEAR :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ schoolYearDisplay(enroll.sy.description) }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                TERM :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ extracTerm(enroll.term) }}
                            </div>
                        </div>
                        
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SUBJECT CODE</th>
                                <th>DESCRIPTIVE TITLE</th>
                                <th>UNIT(S)</th>
                                <th>TIME & DAYS</th>
                                <th>TEACHER</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(ls,indx) in  enroll.ensched" :key="indx">
                                <td>{{ ls.schedule.subjectd.subject_code }}</td>
                                <td>{{ ls.schedule.subjectd.descriptive_title }}</td>
                                <td>{{ ls.schedule.subjectd.unit  }}</td>
                                <td>{{  extractTime(ls.schedule.t_from) +" - "+ extractTime(ls.schedule.t_to)}}
                                                | {{ xtractDay(ls.schedule.day) }}</td>
                                <td>
                                    {{ls.schedule.teacherd.last_name}}, {{ ls.schedule.teacherd.first_name }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row d-none d-print-block">
                    <div class="col-md-12">
                        Printed Date : {{ formatDate(new Date()) }}
                    </div>
                </div>
            </div>
        </div>
        
       
     </main>
</template>

<script>
export default {
    data(){
        return{
            student:{},
            school:{},
            enroll:{
                sectiond:{},
                sy:{},
            }
        }
    }, 
    methods: {
         getEnroll(id){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/enroll/'+id).then(res=>{
                    this.enroll = res.data;
                })
            });
        },
        getStudent(id){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/student/'+id).then(res=>{
                    this.student = res.data;
                })
            });
        },
        getSchool(id){
            this.$axios.get('sanctum/csrf-cookie').then(response=>{
                this.$axios.get('api/school/').then(res=>{
                    this.school = res.data;
                })
            });
        },
        extractGender(num){
            return num == 1 ? "Male" : "Female";
        },
        extractCS(num){
            return num == 1 ? "Single" : num == 2 ? "Married" : num == 3 ? "Legally Separated" : num == 4 ? "Annulled" : num == 5 ? "Widow/er" : "";
        },
        extractCZ(num){
            return num == 1 ? "Filipino" : num == 2 ? "Dual Citizen" : num == 3 ? "Foreign National" : "";
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
        extracTerm(num){
            return num == 1 ? "1st" : num == 2 ? "2nd" : "None";
        },
        printReport(){
            window.print();
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
    
    },
    mounted(){
        let id = this.$route.params.id
        this.getEnroll(id);
        this.getStudent(id);
        this.getSchool();
    }
}
</script>

<style>

</style>
