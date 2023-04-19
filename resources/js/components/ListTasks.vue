<template>
    <div class="text-center">
        <div class="container my-4">
            <div class="row">
                <div class="col-4">
                    <button class="btn btn-primary mt-4 px-3 py-2" @click="navigateToCreateTask">Create Task</button>
                </div>
                <div class="col-4 ms-auto">
                    <h4 class="text-start"><label class="font-sm color-text-mutted mb-10">Projects</label>
                    </h4>
                    <select class="form-select " v-model="selectedProject" @change="onSelectedProject">
                        <option :key="project.id" v-for="project in projects" :value="project.id">{{
                            project.name }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div v-if="tasks.length == 0">
            <h4>There are no tasks for this project</h4>
        </div>
        <div v-else>
            <h1 class="my-4">Tasks</h1>
            <draggable v-model="tasks" @end="reorderTasks">
                <template #item="{ index }">
                    <div class="row">
                        <div class="col-xl-8 offset-xl-2 col-lg-8 offset-lg-2 mb-3">
                            <div class="container">
                                <div class="card text-start">
                                    <div class="card-body">
                                        <h5 class="card-title">Name: <span>{{ tasks[index].name }}</span> </h5>
                                        <p><strong> Priority: {{ tasks[index].priority }}</strong></p>


                                        <div>
                                            <button class="btn btn-success me-2"
                                                @click="navigateToUpdateTask(tasks[index])">Update</button>
                                            <button class="btn btn-danger"
                                                @click.prevent="deleteTask(tasks[index].id)">Delete</button>
                                            <p style="float: right;">Created {{ $filters.timeAgo(tasks[index].created_at) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </draggable>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import draggable from 'vuedraggable'

export default {
    components: {
        draggable
    },
    data() {
        return {
            selectedProject: 1,
            projects: [],
            tasks: [],
        };
    },
    methods: {
        async reorderTasks() {
            const formData = {
                tasks: this.tasks
            }
            await axios.post('reorder-tasks', formData)
                .then(response => {
                    let length = this.tasks.length;
                    this.tasks.forEach(function (task, key) {
                        task.priority = length - key;
                    })
                }).catch(error => {
                    console.log(error)
                })
        },
        async onSelectedProject() {
            const queryParams = {
                project_id: this.selectedProject
            };
            await axios.get('tasks', { params: queryParams })
                .then(response => {
                    this.tasks = [];
                    let responseData = response.data.data;
                    responseData.forEach(element => {
                        this.tasks.push(element)
                    });
                })
                .catch(error => {
                    console.log(error)
                })
        },
        async deleteTask(taskId) {
            await axios.delete(`tasks/${taskId}`)
                .then(response => {
                    alert('Task deleted successfully')
                    this.tasks = [];
                    let responseData = response.data.data;
                    responseData.forEach(element => {
                        this.tasks.push(element)
                    });
                })
                .catch(error => {
                    console.log(error)
                })
        },
        navigateToCreateTask() {
            localStorage.removeItem('name')
            localStorage.removeItem('project_id')
            localStorage.removeItem('task_id')
            this.$router.push('task-form');
        },
        navigateToUpdateTask(task) {
            localStorage.setItem('task_id', task.id)
            this.$router.push({
                name: 'TaskForm'
            });
        }
    },
    async created() {
        await axios.get('projects')
            .then(response => {
                let responseData = response.data.data;
                responseData.forEach(element => {
                    this.projects.push(element)
                });
            })
            .catch(error => {
                console.log(error)
            })

        const queryParams = {
            project_id: this.selectedProject
        };
        await axios.get('tasks', { params: queryParams })
            .then(response => {
                this.tasks = [];
                let responseData = response.data.data;
                responseData.forEach(element => {
                    this.tasks.push(element)
                });
            })
            .catch(error => {
                console.log(error)
            })
    },
    mounted() {
        const projectId = localStorage.getItem('project_id');
        if (projectId) {
            this.selectedProject = projectId;
        }
    }
}
</script>

<style scoped>
.card {
    cursor: pointer;
}

span {
    color: #5477ec;
}

.card {
    background-color: #FFFFFF;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    position: relative;
    top: 0;
    transition: top ease 0.3s;
}

.card:hover {
    top: -2px;
}
</style>