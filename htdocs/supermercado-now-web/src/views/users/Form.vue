<template>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">{{ cardTitle }}</h3>
        </div>
        <form @submit.prevent="save()">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="">Nome</label>
                        <input v-model="user.name" required type="text" class="form-control" />
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="">E-mail</label>
                        <input v-model="user.email" required type="email" class="form-control" />
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="">Nível</label>
                        <select required v-model="user.role" class="form-control">
                            <option v-for="(role, index) in roles" :key="index" :value="index">{{ role }}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="">Senha</label>
                        <input :required="!userId" v-model="user.password" type="password" class="form-control" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">Repita a senha</label>
                        <input v-model="user.password_confirmation" :required="!userId" type="password" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <button @click="$router.go(-1)" type="button" class="btn btn-warning float-left"><span class="fas fa-arrow-left"></span> Voltar</button>
                        <button class="btn btn-success float-right"><span class="fas fa-check"></span> Salvar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import api from "@/api";
import { roles } from "@/utils/constants";
export default {
    data() {
        return {
            user: {
                name: "",
                email: "",
                role: "defaultUser",
                password: "",
                password_confirmation: ""
            },
            userId: null,
            cardTitle: "Cadastrar usuário"
        };
    },
    computed: {
        roles: function() {
            return roles;
        }
    },
    mounted() {
        this.userId = this.$route.params.id;
        if (this.userId) {
            this.cardTitle = "Editar usuário";
            this.getUser(this.userId);
        }
    },
    methods: {
        save() {
            if (this.userId) {
                this.update();
            } else {
                this.store();
            }
        },
        async store() {
            try {
                await api.post("/users", this.user);
                this.showSuccessMessage("Usuário cadastrado com sucesso!");
                this.$router.push("/users");
            } catch (error) {
                this.showErrorMessages(error);
            }
        },
        async update() {
            try {
                await api.put(`/users/${this.userId}`, this.user);
                this.showSuccessMessage("Usuário atualizado com sucesso!");
                this.$router.push("/users");
            } catch (error) {
                this.showErrorMessages(error);
            }
        },
        async getUser(id) {
            const response = await api.get(`/users/${id}`);
            this.user = response.data;
        }
    }
};
</script>

<style>
</style>