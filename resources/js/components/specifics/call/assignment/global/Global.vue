<script lang="ts">
import {computed, onMounted, ref, Ref, watchEffect} from "vue";
import {useStore} from "vuex";
import {Icons} from "../../../../generics/Icon/Icons";
import Icon from "../../../../generics/Icon/Icon.vue";
import {LibraryEnum} from "../../../../../enums/Library";
import {Assignment} from "../../../../../enums/Assignment";
import {Components} from "../../../../../vars/Components";
import {AssignmentType} from "../../../../../types/call/AssignmentType";
import {UHttp} from "../../../../../utils/UHttp";
import {GlobalVars} from "../../../../../vars/Urls";
import {UAssignment} from "../../../../../utils/UAssignment";

export default  {
  name: Assignment.Global,
  components: {
    Icon
  },
  props: {
    idCategoryAssignment: {
      type: Number,
      required: true
    }
  },
computed: {
  Icons() {
    return Icons
  }
},
  setup(props){
    const store = useStore()
    let heads = [Assignment.th1,Assignment.th2, Assignment.th3, ""]
    let assignments = computed(()=> store.getters.findAllAssignment(props.idCategoryAssignment))
    let diffusionTypes = computed(()=> store.getters.findAllDiffusionType)
    const searchTerm: Ref<String>= ref('');
    const searchResults: Ref<AssignmentType[]> = ref([])
    let isPlaying: Ref<boolean> = ref(false)
    let indexPlaying: Ref<number> = ref(undefined)
    let audioElements: Ref<HTMLAudioElement[]> = ref([])
    function labraryValueTrue(value) {
      const notEmptyValues = ["0", 0, undefined, null];
      return notEmptyValues.includes(value) ? Assignment.aucun : value;
    }

    function searchAssignment() {
      if (searchTerm.value !== '') {
        searchResults.value = assignments.value.filter((assignment: any) =>
            assignment.nom.includes(searchTerm.value)
        );
      } else {
        searchResults.value = assignments.value;
      }
    }
    function editGlobal(id: any) {
        store.commit(Assignment.findByIdAssignmentMutation, {category: Assignment.GLOBAUX, id})
        store.commit(Assignment.openModal, Components.EditGlobal)
    }
    async function togglePlayback(file: string, index: number) {
      let audioElement = audioElements.value[index];

      const response = await UHttp.get(`${GlobalVars.BASE_URL_API}library/audio/${file}`, { responseType: 'blob' });

      if (response && response.ok) {
        const audioBlob = await response.blob();
        const audioURL = URL.createObjectURL(audioBlob);

        // Si l'élément audio n'existe pas, créez-en un nouveau
        if (!audioElement) {
          audioElement = new Audio(audioURL);
          audioElements.value[index] = audioElement;

          // Écouteur d'événement pour gérer la fin de la lecture
          audioElement.addEventListener('ended', () => {
            if (index === indexPlaying.value) {
              indexPlaying.value = null;
              audioElements.value[index] = null;
            }
          });
        }
        // Vérification et pause de l'élément précédent s'il est en cours de lecture
        if (indexPlaying.value !== undefined && indexPlaying.value !== index) {
          const currentIndex = indexPlaying.value;
          const previousAudio = audioElements.value[currentIndex];
          if (previousAudio && !previousAudio.paused) {
            previousAudio.pause();
          }
        }
        if (indexPlaying.value === index) {
          if (!audioElement.paused) {
            audioElement.pause();
            indexPlaying.value = index;
          }
          else {
            await audioElement.play();
            indexPlaying.value = index
          }
        }
        else {
          // Démarrer la lecture du nouvel élément
          await audioElement.play();
          indexPlaying.value = index;
        }
      }
    }

    watchEffect(() => {
      searchAssignment()
    });
    onMounted(() => {
      store.dispatch(Assignment.findAllAssignmentAction, props.idCategoryAssignment)
      store.dispatch(Assignment.findAllDiffusionTypeAction)
    })
    return{
      heads,
      assignments,
      LibraryEnum,
      searchTerm,
      searchResults,
      isPlaying,
      indexPlaying,
      audioElements,
      diffusionTypes,
      Assignment,
      labraryValueTrue,
      editGlobal,
      searchAssignment,
      togglePlayback,
      UAssignment
    }
  },
}


</script>
<template>
  <div class="search">
    <Icon :name="Icons.IconSharpSearch" cursor-pointer class="icon"/>
    <input type="text" name="entrypoint" id="entrypoint"
           placeholder="Rechercher par nom"
           v-model="searchTerm" @input="searchAssignment"
    />
  </div>
  <table>
    <thead>
      <th v-for="(head, index) in heads"  :key="index" scope="col">
        {{ head }}
      </th>
    </thead>
    <tbody>
    <tr v-for="(assignment, index) in searchResults">
      <td>{{assignment.nom}}</td>
      <td>
        {{ UAssignment.DiffusionType(assignment.id_type_diffusion, diffusionTypes) }}
        <Icon
            :name="Icons.IconOutlineAudioFile"
            v-if="UAssignment.DiffusionType(assignment.id_type_diffusion, diffusionTypes) === LibraryEnum.AudioMessage"
            cursor-pointer class="icon"
        />
        <Icon
            :name="Icons.IconOutlineHeadsetMic"
            v-if="UAssignment.DiffusionType(assignment.id_type_diffusion, diffusionTypes) === LibraryEnum.VocalSynthesis"
            cursor-pointer class="icon"
        />
      </td>
      <td>
        <div class="col-message">
          <div class="message-active">
            <input
                v-if="UAssignment.labraryValueTrue(assignment.nom_personnalise) !== Assignment.aucun"
                type="checkbox"
                disabled
                :class="[assignment.activable === 0 ? 'opacity-50' : '']"
                class="form-check mr-2 boxchecked:bg-primary block focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 text-primary"
                :checked="assignment.activation" />
            <p  class="cursor-pointer">{{UAssignment.labraryValueTrue(assignment.nom_personnalise)}}</p>
          </div>
          <div>
            <Icon
                :name="Icons.IconCheckIndeterminateSmall"
                cursor-pointer class="icon"
                v-if="UAssignment.labraryValueTrue(assignment.nom_personnalise) === Assignment.aucun ||
                 UAssignment.DiffusionType(assignment.id_type_diffusion, diffusionTypes) === LibraryEnum.VocalSynthesis"
            />
            <Icon
                v-if="UAssignment.labraryValueTrue(assignment.nom_personnalise) !== Assignment.aucun &&
                UAssignment.DiffusionType(assignment.id_type_diffusion, diffusionTypes) === LibraryEnum.AudioMessage"
                :name="indexPlaying === index ? Icons.IconBaselinePauseCircle : Icons.IconBaselinePlayCircle"
                cursor-pointer class="icon"
                @click="togglePlayback(assignment.path_banque_message, index)"
            />
          </div>
        </div>

      </td>
      <td @click="editGlobal(assignment.id_message)">
        <Icon :name="Icons.IconEdit" cursor-pointer class="icon"/>
      </td>
    </tr>
    </tbody>
  </table>
</template>
<style scoped>
.col-message {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.message-active {
  display: flex;
  align-items: center;
}
</style>
