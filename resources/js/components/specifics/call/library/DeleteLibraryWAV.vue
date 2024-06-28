<script lang="ts">
import {Icons} from "../../../generics/Icon/Icons";
import Icon from "../../../generics/Icon/Icon.vue"
import {LibraryType} from "../../../../types/call/LibraryType";
import {useStore} from "vuex";
import {computed, Ref} from "vue";
import {LibraryEnum} from "../../../../enums/Library";
export default {
  name: LibraryEnum.deleteLibraryWAV,
  components: {
    Icon,
  },
  setup() {
    const store = useStore()
    const deleteCurrentLibrary: Ref<LibraryType> = computed(() => store.getters.findByIdLibrary('delete'))
    function deleteLibrary(libraryId: number) {
      store.dispatch(LibraryEnum.deleteLibraryAction, libraryId).then( async (response) => {
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
      deleteLibrary,
      deleteCurrentLibrary,
      libraryEnum: LibraryEnum
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
  <div class="form">
    <h2 class="form-title">{{deleteCurrentLibrary.nom_personnalise}}</h2>

    <div class="form-content-field-delete">
      <div class="form-label-delete">
        <label>{{libraryEnum.label2}}</label>
      </div>
      <p><Icon  :name="Icons.IconOutlineAudioFile"  class="icon"/> {{deleteCurrentLibrary.nom_diffusion}}</p>
    </div>

    <div class="form-content-field-delete">
      <div class="form-label-delete">
        <label>{{libraryEnum.label3}}</label>
      </div>
      <Icon :name="Icons.IconCheckIndeterminateSmall"  class="icon"/>
    </div>

    <div class="form-content-field">
      <div class="form-label">
        <label>{{libraryEnum.label5}}</label>
      </div>
      <p>{{deleteCurrentLibrary.path_banque_message}}</p>
    </div>

    <div class="form-button-container">
      <button
          class="form-button"
          @click="deleteLibrary(deleteCurrentLibrary.id_banque_message)"
          type="submit">{{libraryEnum.submit3}}</button>
    </div>
  </div>
</template>
