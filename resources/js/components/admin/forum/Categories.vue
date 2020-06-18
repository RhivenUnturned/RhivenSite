<template>
    <div id="categories">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <h1 class="h2">Liste des catégories du forum</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <router-link
                    class="btn btn-outline-secondary"
                    :to="{ name: 'categoriescreate' }"
                >
                    <i data-feather="plus-circle"></i>
                    Nouvelle catégorie
                </router-link>
            </div>
        </div>
        <hr />
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Étiquette</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in categories" :key="category.id">
                    <th scope="row">{{ category.id }}</th>
                    <td>{{ category.name }}</td>
                    <td>{{ category.tag_name }}</td>
                    <td>
                        <router-link
                            :to="{
                                name: 'categoriesupdate',
                                params: { categoryId: category.id }
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
                            @click="deleteCategory(category.id)"
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
        this.getCategories();
    },
    data() {
        return {
            categories: {},
            next: null,
            prev: null
        };
    },
    methods: {
        getCategories(address) {
            axios.get(address ? address : "/api/categories").then(response => {
                this.categories = response.data.data;
                this.prev = response.data.links.prev;
                this.next = response.data.links.next;
            });
        },
        deleteCategory(id) {
            axios
                .delete("/api/categories/" + id)
                .then(response => this.getCategories());
        },
        navigate(address) {
            this.getCategories(address);
        }
    }
};
</script>
