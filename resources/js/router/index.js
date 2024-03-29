import {createWebHistory, createRouter} from "vue-router";

import Home from '../page/Home';
import Register from '../page/Registration';

import Admin from '../admin/Mainpage';
import Dashboard from '../admin/Dashboard';
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
import EnrollmentHistory from '../admin/EnrollmentHistory';
import School from '../admin/School';
import User from '../admin/User';
import ClassList from '../admin/ClassList';
import StudentRecord from '../admin/StudentRecord';
import AdminProfile from '../admin/Profile';
import ViewStudent from '../admin/ViewStudent';
import ViewEnrollment from '../admin/ViewEnrollment';
import TransactionLog from '../admin/TransactionLog';

import Student from '../student/Mainpage';
import Profile from '../student/Profile';
import Enroll from '../student/Enroll';
import Scheduled from '../student/Scheduled';
import EnrolledHistory from '../student/EnrolledHistory';
import StudentHome from '../student/Home';


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
                name: 'studenthome',
                path: '',
                component: StudentHome,
            },
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
            },
            {
                name: 'enrolledhistory',
                path: 'enrolled-history',
                component: EnrolledHistory,
            }
        ]
    },
    {
        name: 'admin',
        path: '/admin',
        component: Admin,
        children:[
            {
                path:'',
                name:'dashboard',
                component: Dashboard  
            },
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
            {
                path:'class-list',
                name:'classlist',
                component: ClassList  
            },
            {
                path:'student-record',
                name:'studentrecord',
                component: StudentRecord  
            },

            {
                path:'admin-profile',
                name:'adminprofile',
                component: AdminProfile  
            },
            {
                path:'enrollment-history',
                name:'enrollmenthistory',
                component: EnrollmentHistory  
            },
            {
                path:'view-student/:id',
                name:'viewstudent',
                component: ViewStudent  
            },
            {
                path:'view-enrollment/:id',
                name:'viewenrollment',
                component: ViewEnrollment  
            },
            {
                path:'transaction-log',
                name:'transactionlog',
                component: TransactionLog  
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
    
];

const userRoutes = [
    'student',
    'profile',
    'enroll',
    'scheduled',
    'enrolledhistory',
    'studenthome',
    
   
];
const adminRoutes = [
    'admin',
    'schoolyear',
    'strand',
    'section',
    'teachers',
    'seniorsubject',
    'juniorsubject',
    'schedule',
    'students',
    'viewstudent',
    'studentsarchive',
    'jhsgrade',
    'shsgrade',
    'enrollment',
    'school',
    'user',
    'classlist',
    'studentrecord',
    'adminprofile',
    'dashboard',
    'enrollmenthistory',
    'viewenrollment',
    'transactionlog',
     
];


router.beforeEach((to, from, next)=>{
    if(!window.Laravel.isLoggedin){
        if(openRoutes.includes(to.name)){
            return next();
        }
    }else{
        let user = window.Laravel.user;
        if(adminRoutes.includes(to.name) && (user.role == 1 || user.role == 2)){
            return next();
        }else if(userRoutes.includes(to.name) && user.role == 0){
            return next();
        }
    }
    if(openRoutes.includes(to.name)){
        return next();
    }else{
        return next({name:'home'});
    }
    
   
});

export default router;