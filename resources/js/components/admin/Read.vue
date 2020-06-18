<template>
    <div id="posts">
        <h1>Liste des articles du blog</h1>
        <hr />
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Titre</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="post in posts" :key="post.id">
                    <th scope="row">{{ post.id }}</th>
                    <td>{{ post.title }}</td>
                    <td>
                        <router-link
                            :to="{
                                name: 'update',
                                params: { postId: post.id }
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
                            @click="deletePost(post.id)"
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
        this.getPosts();
    },
    data() {
        return {
            posts: {},
            next: null,
            prev: null
        };
    },
    methods: {
        getPosts(address) {
            axios.get(address ? address : "/api/posts").then(response => {
                this.posts = response.data.data;
                this.prev = response.data.links.prev;
                this.next = response.data.links.next;
            });
        },
        deletePost(id) {
            axios.delete("/api/posts/" + id).then(response => this.getPosts());
        },
        navigate(address) {
            this.getPosts(address);
        }
    }
};
</script>
