<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <img src="@/assets/img/AdminLTELogo.png" alt="AdminLTE" class="brand-image img-circle elevation-3" style="opacity: .8" />
            <span class="pref-logo-text brand-text font-weight-light">NOW</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="@/assets/img/img_default.png" class="img-circle elevation-2" alt="User Image" />
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ user.name }}</a>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li v-if="hasPermission('product.index')" class="nav-item">
                        <router-link to="/" class="nav-link">
                            <i class="fas fa-home nav-icon"></i>
                            <p>Início</p>
                        </router-link>
                    </li>
                    <li v-if="hasPermission('product.index')" class="nav-item">
                        <router-link to="/products" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Produtos</p>
                        </router-link>
                    </li>
                    <li v-if="hasPermission('user.index')" class="nav-item">
                        <router-link to="/users" class="nav-link">
                            <i class="far fa-user nav-icon"></i>
                            <p>Usuários</p>
                        </router-link>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>

<script>
import api from "@/api";
export default {
    data() {
        return {
            user: {
                name: ""
            }
        };
    },
    mounted() {
        this.getUserCurrent();
    },
    methods: {
        async getUserCurrent() {
            const response = await api.get(`/user`);
            this.user = response.data;
            this.$store.commit("setUser", this.user);
            this.$acl.change(['public', ...this.user.permissions]);
        }
    }
};
</script>

<style>
.pref-logo-text {
    margin-left: 30px;
    font-family: "Anton", sans-serif;
    letter-spacing: 3px;
}
.pref-logo-text:after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 118px;
    right: 100px;
    height: 0.5em;
    border-top: 3px solid #fff;
    width: 11px;
}
</style>