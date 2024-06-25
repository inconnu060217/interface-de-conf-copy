<script lang="ts" setup>
import {useStore} from 'vuex';
import { UCookies } from "@/utils/UCookies";
import {computed, onMounted, ref, watchEffect} from "vue";
import { Icons } from "./generics/Icon/Icons";
import Icon from "./generics/Icon/Icon.vue";
const props = defineProps(['title'])


</script>

<template>
    <div class="main-header">
        <div class="container-header">
            <div class="content-text-logo">
              <div class="content-logo">
                <img src="./../assets/Foliateam_Symbole.png" alt="" class="relative inline-flex h-full " />
              </div>
              <div class="content-text">
                <div class="content-choice">
                  <h3 class="">Bienvenue dans la gestion de {{props.title}}</h3>
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
  z-index: 500;
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
.content-lists-option {
    width: 15rem;
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
<!--background-color: rgba(153, 1, 75, 0.3);-->

