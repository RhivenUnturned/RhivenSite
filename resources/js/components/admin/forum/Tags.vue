<template>
    <div id="tags">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <h1 class="h2">Liste des étiquettes du forum</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <router-link
                    class="btn btn-outline-secondary"
                    :to="{ name: 'tagscreate' }"
                >
                    <i data-feather="plus-circle"></i>
                    Nouvelle étiquette
                </router-link>
            </div>
        </div>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Métier</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="tag in tags" :key="tag.id">
                    <th scope="row">{{ tag.id }}</th>
                    <td>{{ tag.name }}</td>
                    <td>{{ tag.job }}</td>
                    <td>
                        <router-link
                            :to="{
                                name: 'tagsupdate',
                                params: { tagId: tag.id }
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
                            @click="deleteTag(tag.id)"
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
        this.getTags();
    },
    data() {
        return {
            tags: {},
            next: null,
            prev: null
        };
    },
    methods: {
        getTags(address) {
            axios.get(address ? address : "/api/tags").then(response => {
                this.tags = response.data.data;
                this.prev = response.data.links.prev;
                this.next = response.data.links.next;
            });
        },
        deleteTag(id) {
            axios
                .delete("/api/tags/" + id)
                .then(response => this.getTags());
        },
        navigate(address) {
            this.getTags(address);
        }
    }
};
</script>
