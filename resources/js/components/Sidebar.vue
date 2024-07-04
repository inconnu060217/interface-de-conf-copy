<script lang="ts" setup>
import { useStore } from 'vuex';
import { UCookies } from "@/utils/UCookies";
import { computed, onMounted, Ref, ref } from "vue";
import { Icons } from "./generics/Icon/Icons";
import Icon from "./generics/Icon/Icon.vue";
import { tableStore } from "@/stores/useTableStore";
import {TSecondaryMenu} from "@/types/TSecondaryMenu.ts";

const currentSettingActive = "";
const isOpen: Ref<boolean> = ref(true);
const props = defineProps<{
    secondaryMenus: TSecondaryMenu[]
}>();

const store = useStore();
const currentMenu = computed(() => tableStore.state.componentName);

function setIsOpen(isO: boolean) {
    isOpen.value = isO;
}

function handleToggleMenu(settingMenu: string, name: string, path: string) {
  /*  if (path !== null && path !== undefined && path !== '') {
        console.log("@@@@")
        store.commit("findPathMutation", { name, path });
    }*/

    store.commit('openTable', settingMenu);
    UCookies.setCookieMenu("currentMenuActive", settingMenu);
}

function backHome() {
    window.location.href = 'app';
}

</script>

<template>
    <div :class="'sidebar-container bg-primary' + (!isOpen ? ' sidebar-close' : '')">
        <div class="close-icon" @click="() => setIsOpen(!isOpen)">
            <Icon :name="isOpen ? Icons.IconArrowLeft : Icons.IconArrowRight"/>
        </div>
        <div class="sidebar-content-container">
            <div class="sidebar-content-wrapper">
                <div class="sidebar-content">
                    <a v-for="route in props.secondaryMenus" :key="route.id" :class="{ active: currentMenu === route.setting }">
                        <Icon @click="handleToggleMenu(route.setting, route.name, route.path)" :name="route.icon" class-name="icon"/>
                        <span
                            v-if="isOpen"
                            @click="handleToggleMenu(route.setting, route.name, route.path)"
                            :class="'' + (currentSettingActive === route.setting ? ' border-b-2 border-white' : '') + (route.activation === 0 ? ' text-gray-300 opacity-50 pointer-events-none border-b-0' : '')"
                        >
              {{ route.name }}
            </span>
                    </a>
                </div>
                <div class="go-back">
                    <Icon :name="Icons.IconHome" cursor-pointer class-name="icon" @click="backHome" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.sidebar-container {
    position: sticky;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: 100%;
    transition: width 300ms ease;
    font-family: 'Roboto', sans-serif;
    z-index: 10;
}

.sidebar-close {
    width: 60px !important;
}

.active {
    background: var(--primary-2-color1);
}

.active::before {
    position: absolute;
    top: 0;
    left: 0;
    content: '';
    height: 100%;
    width: 3px;
    background: #FFFFFF;
}

.close-icon {
    width: 24px;
    height: 24px;
    position: absolute;
    border-radius: 50%;
    top: 85px;
    right: -12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #000000;
    color: #FFFFFF;
    font-size: .65rem;
    cursor: pointer;
    z-index: 30;
}

.close-icon:hover {
    background: #000000;
}

.sidebar-content-container {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-direction: column;
}

.sidebar-content-wrapper {
    width: 100%;
    height: 100%;
    margin: 15px auto;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
}

.sidebar-content-wrapper a {
    position: relative;
    cursor: pointer;
    color: #FFFFFF;
    padding: 5px 15px;
    text-decoration: none;
    font-size: .95rem;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: left;
    flex-wrap: nowrap;
}

.sidebar-content-wrapper a:hover {
    background-color: var(--hover-color);
}

.sidebar-content {
    width: 100%;
}

.sidebar-close .sidebar-content-wrapper a {
    justify-content: center;
}

.sidebar-content i, .sidebar-footer i {
    font-size: 1.4rem;
}

.sidebar-content span, .sidebar-footer span {
    margin-left: 10px;
    width: 100%;
}

.sidebar-footer {
    padding: 25px 0;
}

.icon {
    font-size: 2rem;
    color: #FFFFFF;
}

.sidebar-footer a {
    display: flex;
    align-items: center;
    cursor: pointer;
}
</style>
