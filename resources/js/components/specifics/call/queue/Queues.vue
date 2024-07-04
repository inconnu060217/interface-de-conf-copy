<script lang="ts" setup>
import {useStore} from "vuex";
import Title from "@/components/generics/Title/Title.vue";
import {computed, ref, Ref, watchEffect} from "vue";
import {QueueCallType} from "@/types/TQueueCall";
import {Constants} from "@/constants/Constants";

    const store = useStore()
    const queueCalls = computed(() => store.getters.findQueueCall)
    const searchTerm: Ref<String> = ref('');
    const searchResults: Ref<QueueCallType[]> = ref([]);
    /*watchEffect(() => {
      searchQueueCall()
    })*/
    /*function editQueueCall(queueCallId: number) {
      store.commit(EnumQueue.openModal,Components.EditQueueCall)
      store.commit(EnumQueue.findByIdQueueCallMutation,queueCallId)
    }
    function searchQueueCall () {
      if (searchTerm.value !== '') {
        searchResults.value = queueCalls.value.filter((queueCall: any) =>
            queueCall.nom_file_attente.toLowerCase().includes(searchTerm.value)
        )
      } else {
        searchResults.value = queueCalls.value
      }
    }

   function checkPlanningTrue(namePlanning: string){
      return namePlanning ? namePlanning : EnumQueue.aucun
    }*/
</script>
<template>
  <Title title="File d'attente appels"/>
</template>
