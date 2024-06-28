<script lang="ts">
import { useStore} from "vuex";
import {computed, onMounted, ref, Ref, watchEffect} from "vue";
import {Icons} from "../../../../generics/Icon/Icons";
import Icon from "../../../../generics/Icon/Icon.vue";
import {LibraryEnum} from "../../../../../enums/Library";
import {Assignment} from "../../../../../enums/Assignment";
import {Components} from "../../../../../vars/Components";

import {UAssignment} from "../../../../../utils/UAssignment";
import {UHttp} from "../../../../../utils/UHttp";
import {GlobalVars} from "../../../../../vars/Urls";
import {AssignmentType} from "../../../../../types/call/AssignmentType";
export default  {
  name: Assignment.ExceptionalClosure,
  props: {
    idCategoryAssignment: {
      type: Number,
      required: true
    }
  },
  components: {
    Icon
  },
  computed: {
    Icons() {
      return Icons
    }
  },
  setup(props) {
    let heads = [Assignment.th1,Assignment.th2, Assignment.th3, ""]
    const store = useStore()
    let assignments = computed(() => store.getters.findAllAssignmentExeptionalClosure)
    let diffusionTypes = computed(() => store.getters.findAllDiffusionType)
    const searchTerm: Ref<String>= ref('');
    const searchResults: Ref<AssignmentType[]> = ref([])
    let isPlaying: Ref<boolean> = ref(false)
    let indexPlaying: Ref<number> = ref(undefined)
    let audioElements: Ref<HTMLAudioElement[]> = ref([])

    function searchAssignment() {
      if (searchTerm.value !== '') {
        searchResults.value = assignments.value.filter((assignment: any) =>
            assignment.nom.includes(searchTerm.value)
        );
      } else {
        searchResults.value = assignments.value;
      }
    }

    function editExceptionalClosure(id: number) {
      store.commit(Assignment.findByIdAssignmentMutation, {category: Assignment.EXCEPTIONAL, id})
        store.commit(Assignment.openModal, Components.EditExceptionalClosure)
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
      store.dispatch(Assignment.findAllAssignmentExceptionalClosureAction, props.idCategoryAssignment)
      store.dispatch(Assignment.findAllDiffusionTypeAction)
    })

    return {
      heads,
      assignments,
      LibraryEnum,
      searchTerm,
      searchResults,
      isPlaying,
      indexPlaying,
      audioElements,
      diffusionTypes,
      editExceptionalClosure,
      UAssignment,
      Assignment,
      searchAssignment,
      togglePlayback
    }
  }
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
            <p>{{UAssignment.labraryValueTrue(assignment.nom_personnalise)}}</p>
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
      <td @click="editExceptionalClosure(assignment.id_message)">
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
