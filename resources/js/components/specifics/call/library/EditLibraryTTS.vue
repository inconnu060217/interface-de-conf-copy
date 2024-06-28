<script lang="ts">
import {useStore} from "vuex";
import Icon from "../../../generics/Icon/Icon.vue"
import {Icons} from "../../../generics/Icon/Icons";
import {computed, Ref, ref} from "vue";
import {LibraryType} from "../../../../types/call/LibraryType";
import {LibraryEnum} from "../../../../enums/Library";
export default {
  name: LibraryEnum.editLibraryTTS,
  components: {
    Icon,
  },
  setup() {
    const store = useStore()
    const currentLibrary: Ref<LibraryType> = computed(() => store.getters.findByIdLibrary('edit'))
    const langues = ref([{id: 1, nom: "Français"},{id: 2, nom: "Anglais"}])
    let isError: Ref<boolean> = ref(false)
    let error: Ref<string> = ref("")
    let newLibrary: Ref<LibraryType> = ref(Object.assign({}, currentLibrary.value))
    function editLibrary (event: any) {
      event.preventDefault();
      store.dispatch(LibraryEnum.updateLibraryAction, {data: newLibrary.value, action: LibraryEnum.TTS}).then(async (response) => {
          const jsonResponse = await response.json()
          if(response.ok && response.status === 200) {
          store.commit(LibraryEnum.openAlert, jsonResponse.message )
          store.commit(LibraryEnum.closeModal)
          setTimeout(()=> {
            store.commit(LibraryEnum.closeAlert)
          },3000)
        }
      })
    }

    return {
      editLibrary,
      langues,
      error,
      isError,
      newLibrary,
      libraryEnum: LibraryEnum,
    }
  },
  computed: {
    Icons() {
      return Icons
    }
  },
}
</script>
<template>
  <form class="form" @submit="editLibrary">
    <h2 class="form-title">{{newLibrary.nom_personnalise}}</h2>

    <div class="form-content-field-edit">
      <div class="form-label">
        <label>{{libraryEnum.label2}}</label>
      </div>
      <p><Icon  :name="Icons.IconOutlineHeadsetMic" class="icon"/> {{libraryEnum.p1}}</p>
    </div>

    <div class="form-content-field-edit">
      <div class="form-label">
        <label>{{libraryEnum.label3}}</label>
      </div>
      <p>{{newLibrary.nom_lang}}</p>
    </div>

    <div class="form-content-field">
      <div class="form-label">
        <label>{{libraryEnum.label4}}</label>
      </div>
      <textarea
          :class="{' border-2 border-red-600':  isError && !newLibrary.contenu_tts}"
          v-model="newLibrary.contenu_tts"
          class="form-input">
      </textarea>
    </div>

    <div class="form-content-field">
      <p class=" text-center text-red-600" v-if="isError"
      > <Icon  :name="Icons.IconOutlineError"  /> {{ error }}</p>
    </div>

    <div class="form-button-container">
      <button class="form-button" :disabled="false" type="submit" >{{libraryEnum.submit2}}</button>
    </div>
  </form>
</template>
