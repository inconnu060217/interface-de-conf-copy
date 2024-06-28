<script lang="ts" setup>
//import Alert from "../alert/Alert.vue";

</script>

<template>
  <div class="main-container-content" ref="scrollContainer">
    <div class="main-content">
      <slot></slot>
    </div>
  </div>

</template>

<style scoped>
.main-container-content {
  width: calc(100vw - 250px);
  background: var(--background);
  position: relative;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}
.main-content {
  padding: 0.75rem 1rem;
  background: #FFFFFF;
  border-radius: 0.375rem;
    overflow: auto;
  width: 95%;
    margin: 1.5rem auto 1.5rem auto ;
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
}
</style>
