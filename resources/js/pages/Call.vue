<script lang="ts" setup>
import Sidebar from "@/components/Sidebar.vue";
import Spinner from "@/components/generics/loader/Spinner.vue";
import {loaderStore} from "@/stores/useLoaderStore";
import Header from "@/components/Header.vue";
import Content from "@/components/generics/content/Content.vue";
import Table from "@/components/generics/Table/Table.vue"
import {onMounted, Ref, ref, watch} from "vue";
import {TGroup} from "@/types/TGroup";
import {SGroup} from "@/services/SGroup";
import {SMenu} from "@/services/SMenu";
import {TSecondaryMenu} from "@/types/TSecondaryMenu";
import {useStore} from "vuex";
import {UCookies} from "@/utils/UCookies";
import {ActionStore} from "@/constants/ActionStore";
import Modal from "@/components/generics/Modal/Modal.vue";

const store = useStore();
const sGroup = new SGroup();
const menuService: SMenu = new SMenu()
const groups = ref<TGroup[]>([]);
const secondaryMenus: Ref<TSecondaryMenu[]> = ref([])
const currentMenu = UCookies.getCookie("currentMenuActive");
watch(secondaryMenus, (newValue) => {
    const result = newValue.find((item: any) => item.setting === currentMenu)
    if(result){
        store.commit("openTable", currentMenu)
    } else {
        let firstElement = newValue[0].setting
        UCookies.setCookieMenu("currentMenuActive", firstElement)
        store.commit("openTable", firstElement)
    }
});

function urlMediaCurrent() {
    let url = window.location.href;
    let path = url.split("/");
    return path[path.length - 1];
}

const handleGetRequests= async () => {
    try {
        loaderStore.mutations.openSpinner()
        const responseGroup = await sGroup.handleGetGroup();
        const responseMenu = await menuService.handleGetSecondaryMenu(urlMediaCurrent())
        await store.dispatch(ActionStore.FIND_ALL_ENTRY_POINT_ACTION)
        await store.dispatch(ActionStore.FIND_ALL_ENTRY_POINT_TYPE_ACTION)
        await store.dispatch(ActionStore.FIND_ALL_SVIS_ACTION)
        await store.dispatch(ActionStore.FIND_ALL_QUEUE_CALL_WEB_SERVICE_ACTION)
        groups.value = responseGroup.data;
        secondaryMenus.value = responseMenu.data
    } catch (e) {
        console.error(e);
    }finally {
        loaderStore.mutations.closeSpinner();
    }
};



onMounted(() => {
    handleGetRequests()
});

</script>
<template>
    <div class="main-call">
        <Header :title="'vos paramètres d\'appels'" :groups="groups"/>
        <div class="container-call">
            <div class="content-call" v-if="!loaderStore.state.isLoading">
                <Sidebar  :secondaryMenus="secondaryMenus"/>
                <Content>
                    <Table />
                    <Modal />
                </Content>
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
