<script lang="ts">
import {Components} from "../../../../vars/Components";
import {useStore} from "vuex";
import {UQueue} from "../../../../utils/UQueue";
import Title from "../../../generics/Title/Title.vue";
import {Icons} from "../../../generics/Icon/Icons";
import Icon from "../../../generics/Icon/Icon.vue"
import {computed, ref, Ref, watchEffect} from "vue";
import {QueueCallType} from "../../../../types/call/QueueCallType.js";
import {EnumQueue} from "../../../../enums/Queue";

export default {
  name: EnumQueue.name,
  components: {
    Title,
    Icon
  },
  setup() {
    const store = useStore()
    const queueCalls = computed(() => store.getters.findQueueCall)
    const searchTerm: Ref<String> = ref('');
    const searchResults: Ref<QueueCallType[]> = ref([])
    watchEffect(() => {
      searchQueueCall()
    })
    function editQueueCall(queueCallId: number) {
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
    }
    return {
      searchQueueCall,
      editQueueCall,
      checkPlanningTrue,
      UQueue,
      EnumQueue,
      searchResults,
      searchTerm,
      store
    }
  },
  data(){
      return {
        head: [
          EnumQueue.th1,
          EnumQueue.th2,
          EnumQueue.th3,
          EnumQueue.th4,
          EnumQueue.th5,
          EnumQueue.th6,
          EnumQueue.th7,
          EnumQueue.th8,
          EnumQueue.th9,
          EnumQueue.th10,
          EnumQueue.th11,
          EnumQueue.th12,
          EnumQueue.th13,
          EnumQueue.th14,
          ""
        ],
      }
  },

  computed: {
    Icons() {
      return Icons
    }
  },
  mounted() {
    this.store.dispatch(EnumQueue.findAllQueueCallAction)
  }
}
</script>
<template>
  <Title :title="EnumQueue.title"/>
    <div class="search">
      <Icon :name="Icons.IconSharpSearch" cursor-pointer class="icon"/>
      <input type="text" name="queueCall" id="queueCall"
             placeholder="Rechercher par nom" v-model="searchTerm" @input="searchQueueCall"
      />
    </div>
    <table >
      <thead>
      <tr>
        <th v-for="(item, index) in head" :key="index" scope="col">{{ item }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(queueCall) in searchResults" :key="queueCall.id_file_attente">
        <td>{{ queueCall.nom_file_attente }}</td>
        <td>{{ checkPlanningTrue(queueCall.nom_fa_planning) }}</td>
        <td >{{UQueue.ConvertSecondsToTime(queueCall.duree_dissuasion_tae)}}</td>
        <td>{{UQueue.ConvertSecondsToTime(queueCall.duree_dissuasion_reel)}}</td>
        <td>{{ queueCall.ratio_agent_attente }}%</td>
        <td>{{ queueCall.nom_debordement }}</td>
        <td v-if="queueCall.nom_debordement === EnumQueue.renvoi" >{{ queueCall.renvoi }}</td>
        <td v-else><Icon :name="Icons.IconCheckIndeterminateSmall" cursor-pointer class="icon"/></td>
        <td v-if="queueCall.nom_debordement === EnumQueue.fileAttente" >{{ UQueue.queueOverflowCkeckValue(queueCall.File_attente_debordement)}}</td>
        <td v-else ><Icon :name="Icons.IconCheckIndeterminateSmall" cursor-pointer class="icon"/></td>
          <td v-if="queueCall.nom_debordement === EnumQueue.messageDbedordement" >{{ queueCall.nom_debordement_message}}</td>
          <td v-else ><Icon :name="Icons.IconCheckIndeterminateSmall" cursor-pointer class="icon"/></td>
        <td>
          <input type="checkbox"
                 disabled
                 class="form-check boxchecked:bg-primary focus:ring-[#9f4c6f] dark:focus:ring-[#9f4c6f] dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 text-[#9f4c6f]"
                 :checked="queueCall.callback_attente"
          />
        </td>
        <td>{{ queueCall.propriete }}</td>
        <td>{{ queueCall.numero_presente }}</td>
        <td>{{ queueCall.temps_post_appel }}</td>
        <td>{{ UQueue.ConvertSecondsToTime(queueCall.callback_annonce_time) }}</td>
        <td @click="editQueueCall(queueCall.id_file_attente)">
          <Icon :name="Icons.IconEdit" cursor-pointer class="icon"/>
        </td>
      </tr>
      </tbody>
    </table>
</template>
