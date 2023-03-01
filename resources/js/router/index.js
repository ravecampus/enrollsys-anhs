import {createWebHistory, createRouter} from "vue-router";

import Home from '../page/Home';
import Register from '../page/Registration';

import Admin from '../admin/Mainpage';
import SchoolYear from '../admin/SchoolYear';
import Strand from '../admin/Strand';
import Section from '../admin/Section';
import Teacher from '../admin/Teacher';
import SeniorSubject from '../admin/SeniorSubject';
import JuniorSubject from '../admin/JuniorSubject';
import Schedule from '../admin/Schedule';
import Students from '../admin/Student';
import StudentArchive from '../admin/StudentArchive';
import JHSGrade from '../admin/JHSGrade';
import SHSGrade from '../admin/SHSGrade';
import Enrollment from '../admin/Enrollment';
import School from '../admin/School';
import User from '../admin/User';

import Student from '../student/Mainpage';
import Profile from '../student/Profile';
import Enroll from '../student/Enroll';
import Scheduled from '../student/Scheduled';


export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home,
        children:[
            // {
            //     path:'/',
            //     name:'dashboard',
            //     component: Dashboard  
            // },
            
           
        ]

    },
    {
        name: 'register',
        path: '/register',
        component: Register,
    },
    {
        name: 'student',
        path: '/student',
        component: Student,
        children:[
            {
                name: 'profile',
                path: 'profile',
                component: Profile,
            },
            {
                name: 'enroll',
                path: 'enroll',
                component: Enroll,
            },
            {
                name: 'scheduled',
                path: 'scheduled',
                component: Scheduled,
            }
        ]
    },
    {
        name: 'admin',
        path: '/admin',
        component: Admin,
        children:[
            {
                path:'school-year',
                name:'schoolyear',
                component: SchoolYear  
            },
            {
                path:'strand',
                name:'strand',
                component: Strand  
            },
            {
                path:'section',
                name:'section',
                component: Section  
            },
            {
                path:'teachers',
                name:'teachers',
                component: Teacher  
            },
            {
                path:'senior-subject',
                name:'seniorsubject',
                component: SeniorSubject  
            },
            {
                path:'junior-subject',
                name:'juniorsubject',
                component: JuniorSubject  
            },
            {
                path:'schedule',
                name:'schedule',
                component: Schedule  
            },
            {
                path:'students',
                name:'students',
                component: Students  
            },
            {
                path:'students-archive',
                name:'studentsarchive',
                component: StudentArchive  
            },
            {
                path:'jhs-grade',
                name:'jhsgrade',
                component: JHSGrade  
            },
            {
                path:'shs-grade',
                name:'shsgrade',
                component: SHSGrade  
            },
            {
                path:'enrollment',
                name:'enrollment',
                component: Enrollment  
            },
            {
                path:'school',
                name:'school',
                component: School  
            },
            {
                path:'user',
                name:'user',
                component: User  
            },
    
        ]

    }
    

];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});
const openRoutes = [
    'home',
    'register',
    'admin',
    'schoolyear',
    'strand',
    'section',
    'teachers',
    'seniorsubject',
    'juniorsubject',
    'schedule',
    'students',
    'studentsarchive',
    'jhsgrade',
    'shsgrade',
    'enrollment',
    'school',
    'user',

    'student',
    'profile',
    'enroll',
    'scheduled',
  
   
];

const userRoutes = [
    'home',
    
   
];
const adminRoutes = [
    // 'admin',
    
    
];


router.beforeEach((to, from, next)=>{
    if(!window.Laravel.isLoggedin){
        if(openRoutes.includes(to.name)){
            return next();
        }
    }else{
        let user = window.Laravel.user;
        if(adminRoutes.includes(to.name) && user.role == 1){
            return next();
        }else if(userRoutes.includes(to.name) && user.role == 0){
            return next();
        }
    }
    if(openRoutes.includes(to.name)){
        return next();
    }else{
        return next({name:'signin'});
    }
    
   
});

export default router;