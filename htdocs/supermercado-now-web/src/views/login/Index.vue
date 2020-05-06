<template>
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a><b>SUPERMERCADO</b> NOW</a>
            </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Faça login para iniciar sua sessão</p>

                    <form @submit.prevent="login()">
                        <flash-message style="margin-bottom: 5px" transitionIn="animated swing"></flash-message>
                        <div class="input-group mb-3">
                            <input required v-model="email" ref="email" type="email" class="form-control" placeholder="Email" />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input required v-model="password" type="password" class="form-control" placeholder="Senha" />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from "@/api";

export default {
    name: "Login",
    data() {
        return {
            email: "",
            password: "",
        };
    },
    mounted() {
        this.$nextTick(() => {
            this.$refs.email.focus();
        });
    },
    methods: {
        async login() {
            const { email, password } = this;

            try {
                const response = await api.post(`/login`, {
                    email,
                    password,
                });
                if (response.status === 401) {
                    this.flash("Usuário ou senha inválidos", "error", { timeout: 10000 });
                    return;
                }

                const data = response.data;

                if (data.access_token) {
                    window.localStorage.setItem(this.$store.state.TOKEN_KEY, data.access_token);

                    /* Buscando dados do usuário através do token recuperado */
                    const user = await api.get(`/user`);
                    this.$store.commit("setUser", user.data);
                    this.$acl.change(['public', ...user.data.permissions]);
                    this.$router.push("/");
                }
            } catch (error) {
                if (error.response.status === 401) {
                    if (error.response.data.message && !error.response.data.message.includes("Unauthenticated")) {
                        this.flash(error.response.data.message, "error", { timeout: 10000 });
                    } else {
                        this.flash("Usuário ou senha inválidos", "error", { timeout: 10000 });
                    }
                }
            }
        },
    }
};
</script>

<style scoped>
</style>
