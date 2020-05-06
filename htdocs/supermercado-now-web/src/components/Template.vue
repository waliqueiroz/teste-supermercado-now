<template>
    <div class="wrapper">
        <Navbar />
        <Sidebar />
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{{ $route.meta.description }}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <router-view />
                </div>
            </section>
        </div>
        <Footer />
    </div>
</template>

<script>
import Navbar from "./Navbar";
import Sidebar from "./Sidebar";
import Footer from "./Footer";
export default {
    components: {
        Navbar,
        Sidebar,
        Footer
    }
};
</script>

<style>
</style>