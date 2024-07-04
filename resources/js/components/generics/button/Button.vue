<script lang="ts" setup>
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
    },
    alignLeft: {
        type: Boolean,
        default: false
    },
    cancel: {
        type: Boolean,
        default: false
    }
});
</script>
<template>
    <div :class="{ 'flex justify-end': !props.alignLeft, 'flex justify-start': props.alignLeft}">
        <button
            @click="props.handleFunction"
            type="button"
            :class="[
                props.cancel ? 'bg-red-600' : 'bg-secondary',
                'inline-flex items-center min-w-[6rem] justify-center rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-hoverBtn focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-hoverBtn'
              ]"
        >
            <span v-if="!isDisabled">{{ props.text }}</span>
            <div v-else  class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
        </button>
    </div>

</template>
<style scoped>
.lds-ellipsis {
    display: inline-block;
    position: relative;
    width: 64px;
    height: 16px;
}
.lds-ellipsis div {
    position: absolute;
    top: 20%;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: currentColor;
    animation-timing-function: cubic-bezier(0, 1, 1, 0);
    transform: translateY(-50%);
}
.lds-ellipsis div:nth-child(1) {
    left: 6px;
    animation: lds-ellipsis1 0.6s infinite;
}
.lds-ellipsis div:nth-child(2) {
    left: 6px;
    animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(3) {
    left: 26px;
    animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(4) {
    left: 45px;
    animation: lds-ellipsis3 0.6s infinite;
}
@keyframes lds-ellipsis1 {
    0% {
        transform: scale(0);
    }
    100% {
        transform: scale(1);
    }
}
@keyframes lds-ellipsis3 {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(0);
    }
}
@keyframes lds-ellipsis2 {
    0% {
        transform: translate(0, 0);
    }
    100% {
        transform: translate(19px, 0);
    }
}
</style>

