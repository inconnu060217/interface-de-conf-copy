<script lang="ts" setup>
import {ref, Ref} from "vue";


const isLoading: Ref<boolean> = ref(true);
</script>
<template>
    <div class="content-spinner">
        <div class="content-logo">
            <div class="logo">
                <img src="../../../assets/FOLIATEAM_LOGO.png" alt="" class="relative inline-flex h-full" />
            </div>
        </div>
        <div class="spinner"></div>
    </div>

</template>
<style scoped>
.content-spinner {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: var(--primary-color3);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.content-logo {
    text-align: center;
}
.logo {
    width: 15rem;
    height: 6rem;
}
img {
    height: 100%;
}
.spinner {
    border: 16px solid var(--primary-color1);
    border-top: 16px solid var(--primary-color2);
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
