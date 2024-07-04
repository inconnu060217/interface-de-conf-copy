<script setup lang="ts">
import { ref, computed, onMounted, watchEffect } from 'vue';
import { useStore } from 'vuex';
import { UCookies } from '@/utils/UCookies';
import {
    Select,
    SelectTrigger,
    SelectValue,
    SelectContent,
    SelectGroup,
    SelectItem
} from '@/components/shadcn/ui/select';
import { TGroup } from '@/types/TGroup';
const selectedGroup = ref("");

const props = defineProps<{
    title: string,
    groups: TGroup[]
}>();

watchEffect(() => {
    selectedGroup.value = UCookies.checkCookie(props.groups);

});


const urlMediaCurrent = () => {
    const url = window.location.href;
    const path = url.split("/");
    return path[path.length - 1];
};

</script>
<template>
    <div class="main-header">
        <div class="container-header">
            <div class="content-text-logo">
                <div class="content-logo">
                    <img src="./../assets/Foliateam_Symbole.png" alt="" class="relative inline-flex h-full" />
                </div>
                <div class="content-text">
                    <div class="content-choice">
                        <h3 class="">Bienvenue dans la gestion de {{ props.title }}</h3>
                        <Select v-model="selectedGroup"  @update:model-value="UCookies.updateCookie(selectedGroup, props.groups)" v-if="urlMediaCurrent() !== 'advanced'">
                            <SelectTrigger class="w-[250px]">
                                <SelectValue>
                                    {{ selectedGroup }}
                                </SelectValue>
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <template v-for="group in props.groups" :key="group.id_groupe">
                                        <SelectItem :value="group.nom">
                                            {{ group.nom }}
                                        </SelectItem>
                                    </template>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="user-auth">
                        <p>Version 1.0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.main-header {
    color: var(--color-white);
    position: fixed;
    width: 100%;
    height: 100px;
    padding: 15px 0 15px 0;
    background: var(--primary-2-color1);
}
.container-header {
    height: 100%;
}
.content-text {
    width: calc(100% - 150px);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-right: 30px;
}
.content-choice > h3 {
    margin-right: 10px;
    font-weight: bold;
    font-size: 1.3rem;
}
.content-choice {
    display: flex;
    align-items: center;
}
.content-text-logo {
    display: flex;
    height: 100%;
    width: 100%;
    align-items: center;
}
.content-logo {
    width: 150px;
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.content-logo > img {
    height: 100%;
}
.user-auth, .user-auth > span {
    display: flex;
    justify-content: center;
    align-items: center;
}
.user-auth {
    color: var(--color-white);
    flex-direction: column;
}
.user-auth > span {
    text-align: center;
    font-weight: bold;
    width: 3rem;
    height: 3rem;
    padding: 0.5rem;
    color: var(--primary-color1);
    border-radius: 9999px;
    background: var(--color-white);
    font-size: 25px;
}
.user-auth > p {
    margin-top: 2px;
    font-size: 10px;
}
</style>
