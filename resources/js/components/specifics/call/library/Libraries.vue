<script lang="ts">
import {useStore} from "vuex";
import {Components} from "../../../../vars/Components";
import {LibraryEnum} from "../../../../enums/Library";
import Title from "../../../generics/Title/Title.vue";
import {Icons} from "../../../generics/Icon/Icons";
import Icon from "../../../generics/Icon/Icon.vue";
import {computed, onMounted, ref, Ref, watchEffect} from "vue";
import {LibraryType} from "../../../../types/call/LibraryType";
import {GlobalVars} from "../../../../vars/Urls";
import {UHttp} from "../../../../utils/UHttp";
import {ULibrary} from "../../../../utils/ULibrary";
import LibraryUsage from "./LibraryUsage.vue";


export default {
    name: LibraryEnum.name,
  components: {
    Title,
    Icon,
    LibraryUsage
  },
  setup() {
    const store = useStore()
    const libraries = computed(() => store.getters.findAllLibrary)
    const optiontTTS = computed(()=> store.getters.findOptionTTS)
    const searchTerm: Ref<String> = ref('');
    const searchResults: Ref<LibraryType[]> = ref([])
    let isPlaying: Ref<boolean> = ref(false)
    let indexPlaying: Ref<number> = ref(undefined)
    let audioElements: Ref<HTMLAudioElement[]> = ref([])
    let activeSection: Ref<number> = ref(undefined)
    watchEffect(() => {
      searchLibrary()
    })
    function searchLibrary () {
      if (searchTerm.value !== '') {

        searchResults.value = libraries.value.filter((library: any) =>
            library.nom_personnalise.includes(searchTerm.value)
        );
      } else {
        searchResults.value = libraries.value;
      }
    }
    function addLibrary(modalType: string){
      if(modalType === LibraryEnum.VocalSynthesis){
        store.commit(LibraryEnum.openModal,Components.AddLibraryTTS)
      }
      if(modalType === LibraryEnum.AudioMessage){
        store.commit(LibraryEnum.openModal,Components.AddLibraryWAV)
      }
    }
    function editLibrary(libraryId: number, modalType: string){
      if(modalType === LibraryEnum.VocalSynthesis){
        store.commit(LibraryEnum.openModal,Components.EditLibraryTTS)
      }
      if(modalType === LibraryEnum.AudioMessage){
        store.commit(LibraryEnum.openModal,Components.EditLibraryWAV)
      }
      let edit = LibraryEnum.edit
      store.commit(LibraryEnum.findByIdLibraryMutation,{ libraryId, action: edit })
    }
    function deleteLibrary(libraryId: number, modalType: string){
      if(modalType === LibraryEnum.VocalSynthesis){
        store.commit(LibraryEnum.openModal,Components.DeleteLibraryTTS)
      }
      if(modalType === LibraryEnum.AudioMessage){
        store.commit(LibraryEnum.openModal,Components.DeleteLibraryWAV)
      }
      let deleted = LibraryEnum.delete
      store.commit(LibraryEnum.findByIdLibraryMutation, { libraryId, action: deleted })
      store.commit(LibraryEnum.findByIdLibraryMutation, { libraryId, action: deleted })
    }

    function  toggleActiveSection(section: number) {
      if (activeSection.value === section) {
        activeSection.value = null;
      } else {
        activeSection.value = section;
      }
    }

    async function togglePlayback(file: string, index: number) {
      let audioElement = audioElements.value[index];

      const response = await UHttp.get(`${GlobalVars.BASE_URL_API}library/get/audio/${file}`, { responseType: 'blob' });

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
        } else {
          // Démarrer la lecture du nouvel élément
          await audioElement.play();
          indexPlaying.value = index;
        }
      }
    }

    onMounted(()=> {
      store.dispatch(LibraryEnum.findAllLibraryAction)
    })
    return {
      searchLibrary,
      addLibrary,
      editLibrary,
      deleteLibrary,
      togglePlayback,
      toggleActiveSection,
      ULibrary,
      searchTerm,
      searchResults,
      LibraryEnum,
      optiontTTS,
      isPlaying,
      indexPlaying,
      audioElements,
      activeSection
    }
  },
    data() {
      return {
        headsTable: [LibraryEnum.th1, LibraryEnum.th2, LibraryEnum.th3, LibraryEnum.th4,LibraryEnum.th5, LibraryEnum.th6, LibraryEnum.th7],
      };
    },
  computed: {
    Icons() {
      return Icons
    }
  }
}
</script>
<template>
  <Title :title="LibraryEnum.title"/>
  <div>
    <div class="content-search-add">
      <div class="search">
        <Icon :name="Icons.IconSharpSearch" cursor-pointer class="icon"/>
        <input type="text" name="recherche" id="recherche"
               v-model="searchTerm" @input="searchLibrary"
               placeholder="Rechercher un message"
        />
      </div>
      <div class="content-add">
        <div
            @click="addLibrary(LibraryEnum.syntheseVocale)"
            class="content-add"
            :class="{'option-tts':  !ULibrary.optionTTSValue(optiontTTS)}"
        >
          <p class="mr-2">TTS</p>
          <Icon :name="Icons.IconOutlinePlus"/>
        </div>
        <div
            @click="addLibrary(LibraryEnum.messageAudio)"
            class="bg-primary flex items-center px-[10px] py-[1px] rounded-sm cursor-pointer text-base">
          <p class="mr-2">WAV</p>
          <Icon :name="Icons.IconOutlinePlus"/>
        </div>
      </div>
    </div>
    <table>
      <thead>
            <tr>
              <th v-for="(head, index) in headsTable" :key="index" scope="col">{{ head }}</th>
            </tr>
            </thead>
      <tbody>
            <tr v-for="(library, index) in searchResults" :key="index">
              <td>{{ library.nom_personnalise }}</td>
              <td>
                {{ library.nom_diffusion }}
                <Icon :name="Icons.IconOutlineAudioFile" v-if="library.nom_diffusion === LibraryEnum.AudioMessage"  cursor-pointer class="icon"/>
                <Icon :name="Icons.IconOutlineHeadsetMic" v-if="library.nom_diffusion === LibraryEnum.VocalSynthesis" cursor-pointer class="icon"/>
              </td>
              <td>
                <p v-if="library.nom_diffusion === LibraryEnum.VocalSynthesis">Francais</p>
                <Icon v-else :name="Icons.IconCheckIndeterminateSmall" cursor-pointer class="icon"/>
              </td>
              <td v-if="library.nom_diffusion === LibraryEnum.VocalSynthesis" >{{library.contenu_tts}}</td>
              <td v-else >{{library.path_banque_message}}</td>
              <td class="flex justify-center" v-if="library.nomsUtilisation.length > 0" >
                <p class="mr-2">{{ library.nomsUtilisation.length === 1 ? library.nomsUtilisation[0].nom : library.nomsUtilisation[0].nom + '...' }}</p>
                <Icon @click="toggleActiveSection(index)" :name="Icons.IconBaselineRemoveRedEye" cursor-pointer class="icon"/>
                <LibraryUsage :nomsUtilisation="library.nomsUtilisation" :isExpanded="activeSection === index"  @close-infos="activeSection = null"/>
              </td>
              <td v-else >{{ "Aucun" }}</td>
              <td v-if="library.nom_diffusion === LibraryEnum.AudioMessage">
                <Icon @click="togglePlayback(library.path_banque_message, index)" :name="indexPlaying === index ? Icons.IconBaselinePauseCircle : Icons.IconBaselinePlayCircle" cursor-pointer class="icon"/>
              </td>
              <td v-else >
                <Icon :name="Icons.IconCheckIndeterminateSmall" cursor-pointer class="icon"/>
              </td>
              <td >
                <Icon :name="Icons.IconEdit" @click="editLibrary(library.id_banque_message, library.nom_diffusion)" cursor-pointer class="icon"/>
                <Icon v-if="!library.utilisation" @click="deleteLibrary(library.id_banque_message, library.nom_diffusion)" :name="Icons.IconBaselineDelete" cursor-pointer class="icon operation"/>
              </td>
            </tr>
            </tbody>
    </table>
  </div>
</template>
<style scoped>
.operation {
  margin-left: 10px;
}
</style>
