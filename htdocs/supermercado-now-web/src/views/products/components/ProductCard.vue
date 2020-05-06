<template>
    <div class="card">
        <img class="card-img-top" :src="$props.product.image" alt="Card image cap" />
        <div class="card-body">
            <h5 class="card-title">{{ $props.product.name }}</h5>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">R$ {{ Number($props.product.price).toLocaleString("pt-BR") }}</li>
        </ul>
        <div class="card-footer">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    props: ["product"]
};
</script>

<style scoped>
.card-img-top {
    width: 100%;
    height: 15vw;
    object-fit: cover;
}
</style>