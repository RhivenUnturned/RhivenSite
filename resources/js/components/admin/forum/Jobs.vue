<template>
    <div id="categories">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <h1 class="h2">Liste des métiers</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <router-link
                    class="btn btn-outline-secondary"
                    :to="{ name: 'jobscreate' }"
                >
                    <i data-feather="plus-circle"></i>
                    Nouveau métier
                </router-link>
            </div>
        </div>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="job in jobs" :key="job.id">
                    <th scope="row">{{ job.id }}</th>
                    <td>{{ job.name }}</td>
                    <td>{{ job.description }}</td>
                    <td>
                        <router-link
                            :to="{
                                name: 'jobsupdate',
                                params: { jobId: job.id }
                            }"
                        >
                            <button
                                type="button"
                                class="p-1 mx-3 btn btn-warning"
                            >
                                Modifier
                            </button>
                        </router-link>
                        <button
                            type="button"
                            @click="deleteJob(job.id)"
                            class="p-1 mx-3 btn btn-danger"
                        >
                            Supprimer
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            <button
                v-if="next"
                type="button"
                @click="navigate(next)"
                class="m-3 btn btn-primary"
            >
                Suivant
            </button>
            <button
                v-if="prev"
                type="button"
                @click="navigate(prev)"
                class="m-3 btn btn-primary"
            >
                Précédent
            </button>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        feather.replace();
        this.getJobs();
    },
    data() {
        return {
            jobs: {},
            next: null,
            prev: null
        };
    },
    methods: {
        getJobs(address) {
            axios.get(address ? address : "/api/jobs").then(response => {
                this.jobs = response.data.data;
                this.prev = response.data.links.prev;
                this.next = response.data.links.next;
            });
        },
        deleteJob(id) {
            axios
                .delete("/api/jobs/" + id)
                .then(response => this.getJobs());
        },
        navigate(address) {
            this.getJobs(address);
        }
    }
};
</script>
