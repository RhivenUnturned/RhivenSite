<template>
    <div class="container">
        <form>
            <div
                :class="[
                    'form-group m-1 p-3',
                    successful ? 'alert-success' : ''
                ]"
            >
                <span v-if="successful" class="label label-sucess">Créé!</span>
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
                    placeholder="Entrer le nom du métier"
                    required
                />
            </div>
            <div class="form-group">
                <label for="description">Entrer la description du métier</label>
                <textarea
                    ref="description"
                    class="form-control"
                    id="description"
                    rows="1"
                    maxlength="191"
                    aria-describedby="descriptionHelp"
                ></textarea>
                <small id="descriptionHelp" class="form-text text-muted"
                    >191 caractères maximum.</small
                >
            </div>

            <button
                type="submit"
                @click.prevent="create"
                class="btn btn-primary block"
            >
                Envoyer
            </button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            error: false,
            successful: false,
            errors: []
        };
    },
    methods: {
        create() {
            const formData = new FormData();
            formData.append("name", this.$refs.name.value);
            formData.append("description", this.$refs.description.value);

            axios
                .post("/api/jobs", formData)
                .then(response => {
                    this.successful = true;
                    this.error = false;
                    this.errors = [];
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

            this.$refs.name.value = "";
            this.$refs.description.value = "";
        }
    }
};
</script>
