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
                                    ><i data-feather="file-text"></i>
                                    Forum</a
                                >
                                <div class="dropdown-menu">
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
                        </ul>
                    </div>
                </nav>

                <main
                    role="main"
                    class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4"
                >
                    <router-view></router-view>
                </main>
            </div>
        </div>
    </div>
</template>
<style scoped>
body {
    font-size: 0.875rem;
}

.feather {
    width: 16px;
    height: 16px;
    vertical-align: text-bottom;
}

/*
 * Sidebar
 */

.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100; /* Behind the navbar */
    padding: 48px 0 0; /* Height of navbar */
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, 0.1);
}

@media (max-width: 767.98px) {
    .sidebar {
        top: 5rem;
    }
}

.sidebar-sticky {
    position: relative;
    top: 0;
    height: calc(100vh - 48px);
    padding-top: 0.5rem;
    overflow-x: hidden;
    overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
}

@supports ((position: -webkit-sticky) or (position: sticky)) {
    .sidebar-sticky {
        position: -webkit-sticky;
        position: sticky;
    }
}

.sidebar .nav-link {
    font-weight: 500;
    color: #333;
}

.sidebar .nav-link .feather {
    margin-right: 4px;
    color: #999;
}

.sidebar .nav-link.router-link-active,
.sidebar .nav-link.router-link-exact-active,
.sidebar .nav-link.active,
.sidebar .nav-link.active .feather {
    color: #007bff;
}

.sidebar .nav-link:hover .feather {
    color: inherit;
}

.sidebar-heading {
    font-size: 0.75rem;
    text-transform: uppercase;
}

/*
 * Navbar
 */

.navbar-brand {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    font-size: 1rem;
    background-color: rgba(0, 0, 0, 0.25);
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, 0.25);
}

.navbar .navbar-toggler {
    top: 0.25rem;
    right: 1rem;
}

.navbar .form-control {
    padding: 0.75rem 1rem;
    border-width: 0;
    border-radius: 0;
}

.form-control-dark {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.1);
}

.form-control-dark:focus {
    border-color: transparent;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.25);
}
</style>
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
        return {};
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
