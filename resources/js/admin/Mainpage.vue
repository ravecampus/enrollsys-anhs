<template>
<div>
    <header class="app-header"><router-link class="app-header__logo" :to="{name:'dashboard'}">{{title}}</router-link>
      <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <div class="app-search">
          <h5 class="text-white mb-0">
            {{ school.school_name }}
          </h5>
        </div>
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <!-- <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-cog fa-lg"></i> Settings</a></li> -->
            <li><router-link class="dropdown-item" :to="{name:'adminprofile'}"><i class="fa fa-user fa-lg"></i> Profile</router-link></li>
            <li><a class="dropdown-item" @click="logout()" href="#"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" :src="'/user_2.png'" alt="User Image">
        <div>
          <p class="app-sidebar__user-name">{{ user.first_name }} {{ user.last_name }}</p>
          <p class="app-sidebar__user-designation">{{ extractRole(user.role) }}</p>
        </div>
      </div>
      <ul class="app-menu">
        <li><router-link class="app-menu__item" :to="{name:'dashboard'}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></router-link></li>
        <li><router-link class="app-menu__item" :to="{name:'students'}"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Students</span></router-link></li>
        <li><router-link class="app-menu__item" :to="{name:'teachers'}"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Teachers</span></router-link></li>
        <li><router-link class="app-menu__item" :to="{name:'schedule'}"><i class="app-menu__icon fa fa-clock-o"></i><span class="app-menu__label">Schedule</span></router-link></li>
        <li><router-link class="app-menu__item" :to="{name:'enrollment'}"><i class="app-menu__icon fa fa-user-plus"></i><span class="app-menu__label">Enrollment</span></router-link></li>
        <li><router-link class="app-menu__item" :to="{name:'enrollmenthistory'}"><i class="app-menu__icon fa fa-history"></i><span class="app-menu__label">Enrollment History</span></router-link></li>
        <li><router-link class="app-menu__item" :to="{name:'classlist'}"><i class="app-menu__icon fa fa-list"></i><span class="app-menu__label">Class List</span></router-link></li>
        <li><router-link class="app-menu__item" :to="{name:'studentrecord'}"><i class="app-menu__icon fa fa-file-text"></i><span class="app-menu__label">Student Record</span></router-link></li>
         <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-edit"></i><span class="app-menu__label">Grade</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><router-link class="treeview-item" :to="{name:'jhsgrade'}"><i class="icon fa fa-circle-o"></i> JHS</router-link></li>
            <li><router-link class="treeview-item" :to="{name:'shsgrade'}"><i class="icon fa fa-circle-o"></i> SHS</router-link></li>
          </ul>
        </li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-edit"></i><span class="app-menu__label">Setup</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><router-link class="treeview-item" :to="{name:'schoolyear'}"><i class="icon fa fa-circle-o"></i> School Year</router-link></li>
            <li><router-link class="treeview-item" :to="{name:'strand'}"><i class="icon fa fa-circle-o"></i> Strand</router-link></li>
            <li><router-link class="treeview-item" :to="{name:'section'}"><i class="icon fa fa-circle-o"></i> Section</router-link></li>
            <li><router-link class="treeview-item" :to="{name:'juniorsubject'}"><i class="icon fa fa-circle-o"></i> JHS Subject</router-link></li>           
           <li><router-link class="treeview-item" :to="{name:'seniorsubject'}"><i class="icon fa fa-circle-o"></i> SHS Subject</router-link></li>
          </ul>
        </li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-gear"></i><span class="app-menu__label">Settings</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><router-link class="treeview-item" :to="{name:'school'}"><i class="icon fa fa-circle-o"></i> School Setting</router-link></li>
            <li><router-link class="treeview-item" :to="{name:'user'}"><i class="icon fa fa-circle-o"></i> User Setting</router-link></li>
            <!-- <li><router-link class="treeview-item" :to="{name:'user'}"><i class="icon fa fa-circle-o"></i> User</router-link></li> -->
          </ul>
        </li>

      </ul>
    </aside>
    <flashmessage :message="message" :status="status"></flashmessage>
        <router-view @show="flashMessage"></router-view>
</div>
</template>

<script>

import FlashMessage from '../page/FlashMessage.vue';

export default {
  components:{
    flashmessage: FlashMessage,
  },
  data(){
    return{
      	message:'',
			  title:'',
        status:0,
        user:{},
        school:{}
    }
  },
  methods: {
    	flashMessage(data){
             this.showMessage(data)
      },
      showMessage(data){
            this.message = data.message;
            $('.fm-body').show();
            setTimeout(() => {
                $('.fm-body').fadeOut("slow");
            }, 700);
      },
      extractRole(num){
        return num == 1 ? "Teacher" : num == 2 ? "Adinistrator" : "Student";
      },
      logout(){
          this.$axios.get('/sanctum/csrf-cookie').then(response => {
              this.$axios.post('api/logout')
                  .then(response => {
                      if (response.data.success) {
                          window.location.href = "/"
                      } 
                  })
                  .catch(function (error) {
                      console.error(error);
                  });
          })
    },
    getSchoolName(){
        this.$axios.get('sanctum/csrf-cookie').then(response=>{
            this.$axios.get('api/school').then(res=>{
                this.school = res.data;
            });
        });
    }
		
  },
  mounted() {
    this.user = window.Laravel.user;
    this.title = window.Title.app_name;
    this.getSchoolName();
  },

}
</script>

<style>

</style>
