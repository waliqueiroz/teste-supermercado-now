<template>
    <section>
        <div class="row">
            <div v-for="(product, index) in products.data" :key="index" class="col-sm-2 d-flex align-items-stretch">
                <product-card :product="product">
                    <button @click="updateStatus(product, status.UNDER_ANALYSIS, `Enviar '${product.name}' para análise?`)" v-if="$props.statusId == status.PENDING && hasPermission('product.sendToReview')" class="btn btn-sm btn-primary btn-block">
                        <span class="fas fa-arrow-right"></span> Enviar para análise
                    </button>
                    <div v-if="$props.statusId == status.UNDER_ANALYSIS && hasPermission('product.approveOrDisapprove')">
                        <button @click="updateStatus(product, status.APPROVED, `Aprovar '${product.name}'?`)" class="btn btn-sm btn-success btn-block"><span class="fas fa-check"></span> Aprovar</button>
                        <button @click="updateStatus(product, status.DISAPPROVED, `Reprovar '${product.name}'?`)" class="btn btn-sm btn-danger btn-block"><span class="fas fa-times"></span> Reprovar</button>
                    </div>
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
    </section>
</template>

<script>
import api from "@/api";
import ProductCard from "@/views/products/components/ProductCard";
import { status } from "@/utils/constants";
export default {
    components: { ProductCard },
    props: ["statusId"],
    computed: {
        status: function() {
            return status;
        }
    },
    data() {
        return {
            products: {},
            filters: {
                paginate: true
            }
        };
    },
    mounted() {
        this.getProducts();
    },
    methods: {
        async getProducts(page = 1) {
            this.filters.page = page;
            this.filters.status_id = this.$props.statusId;
            const response = await api.get("/products", { params: this.filters });
            this.products = response.data;
        },
        async updateStatus(product, statusId, title) {
            this.$swal
                .fire({
                    title: title,
                    text: "Esta ação não poderá ser desfeita.",
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
                            await api.put(`products/${product.id}/update-status`, { status_id: statusId });
                            this.showSuccessMessage(`Operação realizada com sucesso!`);
                            this.getProducts();
                            this.$emit("updated");
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