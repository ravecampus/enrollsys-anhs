<template>
<div>
    <header class="app-header"><router-link class="app-header__logo" :to="{name:'profile'}">{{title}}</router-link>
      <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <div class="app-search">
          <h5 class="text-white mb-0">
          Alamada National High School
          </h5>
        </div>
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <!-- <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-cog fa-lg"></i> Settings</a></li> -->
            <li><router-link class="dropdown-item" :to="{name:'profile'}"><i class="fa fa-user fa-lg"></i> Profile</router-link></li>
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
          <p class="app-sidebar__user-designation">{{ extractStudType(user.student_type) }}</p>
        </div>
      </div>
      <ul class="app-menu">
        <li><router-link class="app-menu__item" :to="{name:'studenthome'}"><i class="app-menu__icon fa fa-home"></i><span class="app-menu__label">Home</span></router-link></li>
        <li><router-link class="app-menu__item" :to="{name:'enroll'}"><i class="app-menu__icon fa fa-user-plus"></i><span class="app-menu__label">Enroll</span></router-link></li>
        <li><router-link class="app-menu__item" :to="{name:'scheduled'}"><i class="app-menu__icon fa fa-calendar"></i><span class="app-menu__label">Scheduled</span></router-link></li>
        <li><router-link class="app-menu__item" :to="{name:'enrolledhistory'}"><i class="app-menu__icon fa fa-history"></i><span class="app-menu__label">Enrollment History</span></router-link></li>

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
        user:{}
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
        }, 1000);
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
    extractStudType(num){
      return num == 1 ? "JHS" : "SHS";
    }
  },
  mounted() {
      this.user = window.Laravel.user;
      this.title = window.Title.app_name;
  },

}
</script>

<style>

</style>
