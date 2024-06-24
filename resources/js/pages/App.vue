<script lang="ts" setup>
import {ref, onMounted, Ref, watch} from 'vue'
import {MenuService} from "@/services/MenuService";
import {TMainMenu} from "@/types/TMainMenu";
import {TParameter} from "@/types/TParameter";
import Icon from "../components/generics/Icon/Icon.vue";
const menuService: MenuService = new MenuService()
import Spinner from "@/components/generics/loader/Spinner.vue";
import {loaderStore} from "@/stores/useLoaderStore";

const mainManus: Ref<TMainMenu[]> = ref();
const parameters: Ref<TParameter[]> = ref();

const handleGetMainMenu = async () => {
    try {
        loaderStore.mutations.openSpinner()
        const response = await menuService.handleGetMainMenu()
        mainManus.value = response.data.menu
        parameters.value = response.data.parameter
        console.log(response.data)
    } catch (e) {
        console.error(e)
    }finally {
        loaderStore.mutations.closeSpinner();
    }
}
const redirectPage = (path:string) => {
    if (path === "/chat" || path === "/messaging") return;
    window.location.href = path;
}


onMounted(() => {
    handleGetMainMenu()
})
</script>
<template >
    <div class="main-home">
        <div class="main-container-home">
            <div class="content-logo">
                <div class="logo">
                    <img src="../assets/FOLIATEAM_LOGO.png" alt="" class="relative inline-flex h-full" />
                </div>
            </div>
            <div class="content-text-welcome">
                <h1>Bonjour</h1>
                <h2 >Bienvenue dans votre interface de configuration personnalisée.</h2>
                <h4>Veuillez choisir le média sur lequel vous souhaitez apporter des modifications.</h4>
            </div>
            <div class="main-content-navigation">
                <div class="container">
                    <div class="box-container">
                        <div
                            class="box"
                            v-for="(route, index) in mainManus"
                            :key="index"
                            :class="'' + (parameters[0][route.value.toLowerCase()] === 0 ? ' disable-menu' : '')"
                            @click="redirectPage(route.path)"
                        >
                            <h2 >
                                {{ route.name }}
                            </h2>
                            <Icon :name="route.icon" class="icon"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Spinner v-if="loaderStore.state.isLoading" />
    </div>
</template>

<style scoped>
.main-home {
    background: var(--primary-color1);
    height: 100vh;
    width: 100vw;
    display: flex;
    justify-content: center;
    align-items: center;
}
.main-container-home{
    border-radius: 8px;
    background: var(--primary-color3);
    font-weight: 600;
    line-height: 1.5rem;
    font-size: 1.5rem;
}
.content-logo {

}
.logo {
    width: 15rem;
    height: 6rem;
}
img {
    height: 100%;
}
.content-text-welcome {
    border-top-width: 1px;
    border-bottom-width: 1px;
    padding: 1.5rem;
    justify-content: center;
    display: flex;
    line-height: 1.5rem;
    font-weight: 600;
    flex-direction: column;
    font-size: 1.5rem;
    align-items: center;
}
h1 , h4,  span, .content-logo {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}
h2 {
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
    text-align: center;
}
h1, h2 {
    color: var(--primary-color2);
}
span {
    color: var(--color-white);
    padding: 0.5rem;
    border-radius: 9999px;
    width: 3rem;
    height: 3rem;
    margin-left: 0.5rem;
    background: var(--primary-color1);
}
h4{
    color: rgba(107, 114, 128, 0.9);
    font-size: 1rem;
    line-height: 1.5rem;
}
.main-content-navigation {
    border-bottom-width: 1px;
    padding: 1.5rem;
}
.content-settings {
    padding: 1rem;
    display: flex;
    justify-content: flex-end;
}


.box-container {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
}
.box {
    cursor: pointer;
    transition-duration: 200ms;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3), 0 1px 2px -1px rgb(0 0 0 / 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    font-size: 0.875rem;
    line-height: 1.25rem;
    padding: 1rem 0;
    border-radius: 10px;
    font-weight: bold;
}



.box:hover {
    box-shadow: 0 5px 10px var(--secondary-clear-color1);
    transform: scale(1.02);
}


h2 {
    margin-bottom: .5rem;
}
.disable-menu {
    opacity: 0.5;
    pointer-events: none;
}
.icon {
    font-size: 1.5rem;
    color: var(--primary-color1);
}


</style>
