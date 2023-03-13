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
                            Student Information:
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
                                {{ student.lrn }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                LASTNAME :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ student.last_name }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                FIRSTNAME :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ student.first_name}}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                MIDDLENAME :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ student.middle_name}}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                AGE :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ student.age }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                BIRTHDATE :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ formatDate(student.birthdate) }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                BIRTHPLACE :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ student.birth_place }}
                            </div>
                        </div>
                    </div>
             
                    <div class="col-md-6 col-print-6">
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                GENDER :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ extractGender(student.gender) }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                CIVIL STATUS :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ extractCS(student.civil_status) }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                CITIZENSHIP :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ extractCZ(student.citizenship) }}
                            </div>
                        </div>
                        <!-- <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                MIDDLENAME :
                            </div>
                            <div class="col-md-9 col-print-9">
                                {{ student.}}
                            </div>
                        </div> -->
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                RELIGION :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ student.religion }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                ADDRESS :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ student.address }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 col-print-3 text-right pr-0">
                                CONTACT :
                            </div>
                            <div class="col-md-9 col-print-9 b-text">
                                {{ student.contact }}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
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
        }
    }, 
    methods: {
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
        printReport(){
            window.print();
        }
    },
    mounted(){
        let id = this.$route.params.id
        this.getStudent(id);
        this.getSchool();
    }
}
</script>

<style>

</style>
