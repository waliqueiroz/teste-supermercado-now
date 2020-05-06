<template>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">{{ cardTitle }}</h3>
        </div>
        <form @submit.prevent="save()">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="">Nome</label>
                        <input v-model="product.name" required type="text" class="form-control" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">Preço (R$)</label>
                        <input v-model="product.price" required type="number" step="0.01" class="form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="">Imagem</label>
                        <input accept="image/*" :required="!productId" @change="handleFileUpload()" ref="image" type="file" class="form-control" />
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
export default {
    data() {
        return {
            product: {
                name: "",
                price: "",
                image: ""
            },
            productId: null,
            cardTitle: "Cadastrar produto"
        };
    },
    mounted() {
        this.productId = this.$route.params.id;
        if (this.productId) {
            this.cardTitle = "Editar produto";
            this.getProduct(this.productId);
        }
    },
    methods: {
        handleFileUpload() {
            this.product.image = this.$refs.image.files[0];
        },
        save() {
            if (this.productId) {
                this.update();
            } else {
                this.store();
            }
        },
        async store() {
            try {
                let formData = new FormData();
                Object.keys(this.product).forEach(key => formData.append(key, this.product[key]));
                await api.post("/products", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                });
                this.showSuccessMessage(`Produto cadastrado com sucesso!`);
                this.$router.push("/products");
            } catch (error) {
                this.showErrorMessages(error);
            }
        },
        async update() {
            try {
                let formData = new FormData();
                Object.keys(this.product).forEach(key => formData.append(key, this.product[key]));
                formData.append("_method", "PUT");
                await api.post(`/products/${this.productId}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                });
                this.showSuccessMessage(`Produto atualizado com sucesso!`);
                this.$router.push("/products");
            } catch (error) {
                this.showErrorMessages(error);
            }
        },
        async getProduct(id) {
            const response = await api.get(`/products/${id}`);
            this.product = response.data;
        }
    }
};
</script>

<style>
</style>