<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/components/lib/utils';

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <td
    :class="
      cn(
        'p-4 align-middle [&:has([role=checkbox])]:pr-0 text-secondary',
        props.class,
      )
    "
  >
    <slot />
  </td>
</template>
