<template>
    <div class="container">
        <form>
            <div
                :class="[
                    'form-group m-1 p-3',
                    successful ? 'alert-success' : ''
                ]"
            >
                <span v-if="successful" class="label label-sucess"
                    >Modifiée!</span
                >
            </div>

            <div :class="['form-group m-1 p-3', error ? 'alert-danger' : '']">
                <span v-if="errors.title" class="label label-danger">{{
                    errors.title[0]
                }}</span>
                <span v-if="errors.body" class="label label-danger">{{
                    errors.body[0]
                }}</span>
            </div>

            <div class="form-group">
                <input
                    type="text"
                    ref="name"
                    class="form-control"
                    id="name"
                    placeholder="Entrer un nom pour l'étiquette"
                    required
                />
            </div>

            <div class="form-row mb-3">
                <div class="col-9">
                    <label for="job"
                        >Sélectionnez un métier avec accès exclusif</label
                    >
                    <select
                        class="form-control"
                        ref="job"
                        id="job"
                        aria-describedby="jobHelp"
                        v-model="selected"
                        required
                    >
                        <option
                            v-for="job in jobs"
                            :key="job.id"
                            v-bind:value="job.name"
                            >{{ job.id }} - {{ job.name }}</option
                        >
                    </select>
                    <small id="jobHelp" class="form-text text-muted"
                        >Laisser vide pour créer une étiquette publique.
                        Utilisez le bouton réinitialiser pour vider ce
                        champ.</small
                    >
                </div>
                <div class="col position-relative">
                    <button
                        type="button"
                        @click.prevent="reset"
                        class="btn btn-primary block position-absolute mx-auto"
                        style="top: 32px; left:50%;"
                    >
                        Réinitialiser
                    </button>
                </div>
            </div>

            <button
                type="submit"
                @click.prevent="update"
                class="btn btn-primary block"
            >
                Envoyer
            </button>
        </form>
    </div>
</template>

<script>
export default {
    mounted() {
        this.getJobs();
        this.getTag();
    },
    props: {
        tagId: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            selected: "",
            jobs: {},
            error: false,
            successful: false,
            errors: []
        };
    },
    methods: {
        update() {
            let name = this.$refs.name.value;
            let job = this.$refs.job.value;

            axios
                .put("/api/tags/" + this.tagId, { name, job })
                .then(response => {
                    this.successful = true;
                    this.error = false;
                    this.errors = [];
                    this.getTag();
                })
                .catch(error => {
                    if (!_.isEmpty(error.response)) {
                        if ((error.response.status = 422)) {
                            this.errors = error.response.data.errors;
                            this.successful = false;
                            this.error = true;
                        }
                    }
                });
        },
        getTag() {
            axios.get("/api/tags/" + this.tagId).then(response => {
                this.$refs.name.value = response.data.data.name;
                this.selected = response.data.data.job;
            });
        },
        getJobs() {
            axios.get("/api/jobs").then(response => {
                this.jobs = response.data.data;
            });
        },
        reset() {
            this.$refs.job.value = "";
        }
    }
};
</script>
