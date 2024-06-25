<script lang="ts" setup>
import Sidebar from "@/components/Sidebar.vue";
import Spinner from "@/components/generics/loader/Spinner.vue";
import {loaderStore} from "@/stores/useLoaderStore";
import Header from "@/components/Header.vue";
</script>
<template>
    <div class="main-call">
        <Header :title="'vos paramètres d\'appels'"/>
        <div class="container-call">
            <div class="content-call">
                <Sidebar  />
            </div>
        </div>
    </div>
    <Spinner v-if="loaderStore.state.isLoading" />
</template>
<style scoped>
.main-call {
    position: relative;
    width: 100%;
    height: 100%;
}

.container-call {
    position: relative;
    top: 100px;
    height: calc(100vh - 100px);
}
.content-call {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: row;
}
</style>
