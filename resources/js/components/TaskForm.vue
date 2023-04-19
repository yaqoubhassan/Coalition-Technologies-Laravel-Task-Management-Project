<template>
    <div>
        <button class="btn btn-warning mb-5" @click="this.$router.push('/')">Go Back</button>
        <div class="row">
            <div class="col-xl-8 offset-xl-2 col-lg-8 offset-lg-2 mb-3">

                <form action="">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Name</label>
                        <input type="text" class="form-control" v-model="formData.name" placeholder="Enter task name">
                    </div>
                    <label for="exampleFormControlInput1" class="form-label">Project</label>
                    <select class="form-select" v-model="formData.project_id">
                        <option :key="project.id" v-for="project in projects" :value="project.id">{{
                            project.name }}
                        </option>
                    </select>
                    <div class="text-end">
                        <button class="btn btn-primary mt-4" @click.prevent="handleSubmit">{{ buttonText }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            formData: {
                name: '',
                project_id: null,
            },
            projects: [],
        }
    },
    computed: {
        buttonText() {
            return localStorage.getItem('task_id') ? 'Update Task' : 'Create Task'
        }
    },
    async mounted() {
        let taskId = localStorage.getItem('task_id');
        if (taskId) {
            await axios.get(`tasks/${taskId}`)
                .then(response => {
                    let responseData = response.data.data;
                    localStorage.setItem('name', responseData.name)
                    localStorage.setItem('project_id', responseData.project_id)
                    this.formData.name = localStorage.getItem('name');
                    this.formData.project_id = localStorage.getItem('project_id');
                })
                .catch(error => {
                    console.log(error);
                })
        }
    },
    methods: {
        async handleSubmit() {
            let taskId = localStorage.getItem('task_id');
            if (taskId) {
                await axios.patch(`tasks/${taskId}`, this.formData)
                    .then(response => {
                        localStorage.setItem('project_id', this.formData.project_id)
                        alert('Task updated successfully')
                        this.$router.push('/')
                    })
                    .catch(error => {
                        console.log(error)
                    })

            } else {
                await axios.post('tasks', this.formData)
                    .then(response => {
                        localStorage.setItem('project_id', this.formData.project_id)
                        alert('Task created successfully')
                        this.$router.push('/')
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }
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
    }
}
</script>