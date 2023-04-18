import { createRouter, createWebHistory } from 'vue-router'
import Home from '../components/Home.vue'
import ListTasks from '../components/ListTasks.vue'
import TaskForm from '../components/TaskForm.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            components: {
                default: Home
            }, children: [
                { path: '', name: 'ListTasks', component: ListTasks },
                { path: 'task-form', name: 'TaskForm', component: TaskForm }
            ]
        },
        { path: '/:pathMatch(.*)*', redirect: '/' }
    ]
})

export default router