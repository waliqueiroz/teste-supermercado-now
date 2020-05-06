<template>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Todos os usuários</h3>
            <router-link tag="button" type="button" class="btn btn-primary btn-sm float-right" to="/users/create"><span class="fas fa-plus"></span> Novo</router-link>
        </div>
        <div class="card-body">
            <form @submit.prevent="getUsers()">
                <div class="row">
                    <div class="col-sm-4 form-group">
                        <label for="">Nome</label>
                        <input type="text" class="form-control" v-model="filters.name" />
                    </div>
                    <div class="col-sm-3 form-group">
                        <label for="">E-mail</label>
                        <input type="text" class="form-control" v-model="filters.email" />
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="">Nível</label>
                        <select v-model="filters.role" class="form-control">
                            <option value="">Todos os níveis</option>
                            <option v-for="(role, index) in roles" :key="index" :value="index">{{ role }}</option>
                        </select>
                    </div>
                    <div class="col-sm-2 form-group align-self-end">
                        <button class="btn btn-default btn-block"><span class="fas fa-filter"></span> Filtrar</button>
                    </div>
                    <div class="col-sm-1 form-group align-self-end">
                        <button title="Limpar filtros" @click="cleanFilters()" class="btn btn-default btn-block"><span class="fas fa-times-circle"></span></button>
                    </div>
                </div>
            </form>
            <table class="table table-striped">
                <thead>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Nível</th>
                    <th>Nível</th>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in users.data" :key="index">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ roles[user.roles[0].name] }}</td>
                        <td width="5%">
                            <div class="btn-group btn-group-sm">
                                <router-link title="Editar" :to="`/users/${user.id}/edit/`" class="btn btn-info"><i class="fas fa-edit"></i></router-link>
                                <a title="Excluir" @click.prevent="destroy(user)" href="" class="btn btn-danger"><i class="fas fa-times"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-sm-5">Mostrando {{ users.from }} até {{ users.to }} de {{ users.total }} usuários encontrados.</div>
                <div class="col-sm-7">
                    <laravue-pagination align="right" :limit="5" :data="users" v-on:pagination-change-page="getUsers">
                        <span slot="prev-nav">&lt; Anterior</span>
                        <span slot="next-nav">Próximo &gt;</span>
                    </laravue-pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from "@/api";
import { roles } from "@/utils/constants";
export default {
    data() {
        return {
            filters: {
                name: "",
                email: "",
                paginate: true,
                role: ""
            },
            users: {}
        };
    },
    computed: {
        roles: function() {
            return roles;
        }
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        async getUsers(page = 1) {
            this.filters.page = page;
            const response = await api.get("/users", { params: this.filters });
            this.users = response.data;
        },
        cleanFilters() {
            this.filters.name = "";
            this.filters.email = "";
            this.filters.role = "";
            this.getUsers();
        },
        destroy(user) {
            this.$swal
                .fire({
                    title: `Excluir o usuário "${user.name}"?`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
                    reverseButtons: true
                })
                .then(async result => {
                    if (result.value) {
                        try {
                            await api.delete(`/users/${user.id}`);
                            this.showSuccessMessage(`${user.name} excluído com sucesso!`);
                            this.getUsers();
                        } catch (error) {
                            this.showErrorMessages(error);
                        }
                    }
                });
        }
    }
};
</script>

<style>
</style>