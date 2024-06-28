<script lang="ts" setup>
const props = defineProps(['title'])

</script>
<template>
  <div class="main-title">
    <h3>{{ props.title }}</h3>
  </div>
</template>
<style scoped>
.main-title {
  font-size: 1.5rem;
  line-height: 2rem;
  font-family: 'Roboto', sans-serif;
  font-weight: bold;
  color: var(--primary-color1);
}
h3 {
  display: flex;
  justify-content: center;
}
</style>
