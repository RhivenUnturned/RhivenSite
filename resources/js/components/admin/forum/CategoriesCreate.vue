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
                    >Créée!</span
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
                    placeholder="Entrer un nom pour la catégorie"
                    required
                />
            </div>

            <div class="form-group">
                <label for="tag"
                    >Sélectionnez l'étiquette parente de la catégorie</label
                >
                <select class="form-control" ref="tag" id="tag" required>
                    <option
                        v-for="tag in tags"
                        :key="tag.id"
                        v-bind:value="tag.id"
                        >{{ tag.id }} - {{ tag.name }}</option
                    >
                </select>
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
    mounted() {
        this.getTags();
    },
    data() {
        return {
            tags: {},
            error: false,
            successful: false,
            errors: []
        };
    },
    methods: {
        create() {
            const formData = new FormData();
            formData.append("name", this.$refs.name.value);
            formData.append("tag_id", this.$refs.tag.value);

            axios
                .post("/api/categories", formData)
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
            this.$refs.tag.value = "";
        },
        getTags() {
            axios.get("/api/tags").then(response => {
                this.tags = response.data.data;
            });
        }
    }
};
</script>
