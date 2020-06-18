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
                    placeholder="Entrer un nom pour la catégorie"
                    required
                />
            </div>

            <div class="form-group">
                <label for="tag"
                    >Sélectionnez l'étiquette parente de la catégorie</label
                >
                <select class="form-control" ref="tag" id="tag" v-model="selected" required>
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
        this.getCategory();
        this.getTags();
    },
    props: {
        categoryId: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            selected: "",
            tags: {},
            error: false,
            successful: false,
            errors: []
        };
    },
    methods: {
        update() {
            let name = this.$refs.name.value;
            let tag_id = this.$refs.tag.value;

            axios
                .put("/api/categories/" + this.categoryId, { name, tag_id })
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
        },
        getCategory() {
            axios.get("/api/categories/" + this.categoryId).then(response => {
                this.$refs.name.value = response.data.data.name;
                this.selected = response.data.data.tag_id;
            });
        },
        getTags() {
            axios.get("/api/tags").then(response => {
                this.tags = response.data.data;
            });
        }
    }
};
</script>
