<script lang="ts" setup>
import { Icons } from '../Icon/Icons';
import icon from "../Icon/Icon.vue";
import Icon from "@/components/generics/Icon/Icon.vue";
const props = defineProps({
    text: {
        type: String
    },
    handleFunction: {
        type: Function
    },
    isDisabled:{
        type: Boolean,
        default: false
    }
});
</script>
<template>
    <button
        @click="props.handleFunction"
        type="button"
        class="rounded-md bg-secondary px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
    >
        <span v-if="!isDisabled">{{ props.text }}</span>
        <Icon v-else :name="Icons.Icon3DotsScale" class-name="icon"/>
    </button>
</template>
