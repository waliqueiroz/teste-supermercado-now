<template>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Todos os produtos</h3>
            <router-link tag="button" type="button" class="btn btn-primary btn-sm float-right" to="/products/create"><span class="fas fa-plus"></span> Novo</router-link>
        </div>
        <div class="card-body">
            <form @submit.prevent="getProducts()">
                <div class="row">
                    <div class="col-sm-3 form-group">
                        <label for="">Nome</label>
                        <input type="text" class="form-control" v-model="filters.name" />
                    </div>
                    <div class="col-sm-2 form-group">
                        <label for="">Preço mínimo</label>
                        <input step="0.01" type="number" class="form-control" v-model="filters.price_min" />
                    </div>
                    <div class="col-sm-2 form-group">
                        <label for="">Preço máximo</label>
                        <input step="0.01" type="number" class="form-control" v-model="filters.price_max" />
                    </div>
                    <div class="col-sm-2 form-group">
                        <label for="">Status</label>
                        <select class="form-control" v-model="filters.status_id">
                            <option value="">Todos os status</option>
                            <option v-for="(status, index) in statuses" :key="index" :value="status.id">{{ status.name }}</option>
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
            <div class="row">
                <div v-for="(product, index) in products.data" :key="index" class="col-sm-2 d-flex align-items-stretch">
                    <product-card :product="product">
                        <router-link :to="`/products/${product.id}/edit`" tag="button" class="btn btn-sm btn-info btn-block"><span class="far fa-edit"></span> Editar</router-link>
                        <button v-if="hasPermission('product.approveOrDisapprove')" @click="destroy(product)" class="btn btn-sm btn-danger btn-block"><span class="fas fa-trash"></span> Excluir</button>
                    </product-card>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">Mostrando {{ products.from }} até {{ products.to }} de {{ products.total }} produtos encontrados.</div>
                <div class="col-sm-7">
                    <laravue-pagination align="right" :limit="5" :data="products" v-on:pagination-change-page="getProducts">
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
import ProductCard from "./components/ProductCard";
export default {
    components: { ProductCard },
    data() {
        return {
            filters: {
                name: "",
                price_min: "",
                price_max: "",
                status_id: "",
                paginate: true
            },
            products: {},
            statuses: []
        };
    },
    mounted() {
        this.getProducts();
        this.getStatuses();
    },
    methods: {
        async getProducts(page = 1) {
            this.filters.page = page;
            const response = await api.get("/products", { params: this.filters });
            this.products = response.data;
        },
        cleanFilters() {
            this.filters.name = "";
            this.filters.price_min = "";
            this.filters.price_max = "";
            this.filters.status_id = "";
            this.getProducts();
        },
        async getStatuses() {
            const response = await api.get("/statuses");
            this.statuses = response.data;
        },
        destroy(product) {
            this.$swal
                .fire({
                    title: `Excluir o produto "${product.name}"?`,
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
                            await api.delete(`/products/${product.id}`);
                            this.showSuccessMessage(`${product.name} excluído com sucesso!`);
                            this.getProducts();
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