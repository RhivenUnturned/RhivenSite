<template>
    <div>
        <nav
            class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow"
        >
            <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="/"
                ><strong>Rhiven</strong> (retour au site)</a
            >
            <button
                class="navbar-toggler position-absolute d-md-none collapsed"
                type="button"
                data-toggle="collapse"
                data-target="#sidebarMenu"
                aria-controls="sidebarMenu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap">
                    <a class="nav-link" href="#" @click="logout"
                        >Se déconnecter</a
                    >
                </li>
            </ul>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <nav
                    id="sidebarMenu"
                    class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
                >
                    <div class="sidebar-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{
                                        name: 'dashboard',
                                        params: { userId }
                                    }"
                                >
                                    <i data-feather="home"></i>
                                    Accueil
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ name: 'read', params: { userId } }"
                                >
                                    <i data-feather="list"></i>
                                    Voir les articles
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ name: 'create', params: { userId } }"
                                >
                                    <i data-feather="plus-circle"></i>
                                    Nouvel article
                                </router-link>
                            </li>
                            <li class="nav-item dropdown">
                                <a
                                    class="nav-link dropdown-toggle"
                                    data-toggle="dropdown"
                                    href="#"
                                    role="button"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    ><i data-feather="file-text"></i> Forum</a
                                >
                                <div class="dropdown-menu">
                                    <router-link
                                        class="dropdown-item"
                                        :to="{
                                            name: 'tags',
                                            params: { userId }
                                        }"
                                    >
                                        Gérer les étiquettes
                                    </router-link>
                                    <router-link
                                        class="dropdown-item"
                                        :to="{
                                            name: 'categories',
                                            params: { userId }
                                        }"
                                    >
                                        Gérer les catégories
                                    </router-link>
                                </div>
                            </li>
                            <li class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ name: 'jobs' }"
                                >
                                    <i data-feather="tool"></i>
                                    Gérer les métiers
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </nav>

                <main
                    role="main"
                    class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4"
                >
                    <router-view v-bind:userId="userId"></router-view>
                </main>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        userId: {
            type: Number,
            required: true
        },
        userName: {
            type: String,
            required: true
        }
    },
    data() {
        return {
        };
    },
    methods: {
        logout() {
            axios.post("/logout").then(() => {
                window.location = "/";
            });
        }
    }
};
</script>
