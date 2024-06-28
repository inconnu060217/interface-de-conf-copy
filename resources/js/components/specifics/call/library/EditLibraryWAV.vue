<script lang="ts">
import {useStore} from "vuex";
import Icon from "../../../generics/Icon/Icon.vue"
import {Icons} from "../../../generics/Icon/Icons";
import {computed, Ref, ref} from "vue";
import {LibraryType} from "../../../../types/call/LibraryType";
import {LibraryEnum} from "../../../../enums/Library";
import {UCookies} from "../../../../utils/UCookies";
export default {
  name: LibraryEnum.editLibraryWAV,
  components: {
    Icon,
  },
  setup() {

    const store = useStore()
    const currentLibrary: Ref<LibraryType> = computed(() => store.getters.findByIdLibrary('edit'))
    const refFileWav = ref(null);
    const file = ref(null);
    let isError: Ref<boolean> = ref(false)
    let error: Ref<string> = ref("")
    let isNotCompatible: Ref<boolean> = ref(false)
    let disabled: Ref<boolean>= ref(false)
    let newLibrary: Ref<LibraryType> = ref(Object.assign({}, currentLibrary.value))
    function isWavFile(file: any){
      const mimeType = file.type;
      return mimeType === LibraryEnum.typeWav;
    }
    function editLibrary (event: any) {
      event.preventDefault();
      const file = refFileWav.value?.files[0];
      if (!file) {
        isError.value = true
        error.value = LibraryEnum.messageErrorTypeWav1
      }
      else if(!isWavFile(file)) {
        isError.value = true
        error.value = LibraryEnum.messageErrorTypeWav
      }

      else {
          let directory_media = UCookies.getCookie("media_directory")
        newLibrary.value.path_banque_message = `${directory_media}/${file.name}`

        store.dispatch(LibraryEnum.updateFileLibraryAction, {data: newLibrary.value, action: LibraryEnum.WAV, file: file}).then(async (response) => {
          if(response.status === 422) {
              isNotCompatible.value = true
            isError.value = true
            error.value = response.message
          }
          if(response.status === 200) {
            store.commit(LibraryEnum.openAlert, response.message )
            store.commit(LibraryEnum.closeModal)
            setTimeout(()=> {
              store.commit(LibraryEnum.closeAlert)
            },3000)
          }
        })
      }
    }

    function handleConverting() {
      const file = refFileWav.value?.files[0];
      store.dispatch("convertingFileFileLibraryUpdateAction", {data: newLibrary.value, file: file} ).then(async(response) => {
              disabled.value = true
              if(response === 500) {
                  isError.value = true
                  error.value = "Une erreur s'est produite lors de la conversion."
                  setTimeout(()=> {
                      store.commit(LibraryEnum.closeModal)
                  },3000)
              }
              
              if(response.status === 200) {
                  store.commit(LibraryEnum.openAlert, response.message )
                  store.commit(LibraryEnum.closeModal)
                  setTimeout(()=> {
                      store.commit(LibraryEnum.closeAlert)
                  },3000)
              }
          })
    }

    return {
      editLibrary,
      libraryEnum: LibraryEnum,
      error,
      isError,
      newLibrary,
      refFileWav,
      file,
      isNotCompatible,
      currentLibrary,
      disabled,
      handleConverting
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
      <p><Icon :name="Icons.IconOutlineAudioFile" cursor-pointer class="icon"/> {{libraryEnum.p2}}</p>
    </div>

    <div class="form-content-field-edit">
      <div class="form-label">
        <label>{{libraryEnum.label3}}</label>
      </div>
      <Icon :name="Icons.IconCheckIndeterminateSmall" cursor-pointer class="icon"/>
    </div>

    <div class="form-content-field-edit">
      <div class="form-label">
        <label>{{libraryEnum.p3}}</label>
      </div>
      <p>{{currentLibrary.path_banque_message}}</p>
    </div>

    <div class="form-content-field">
      <div class="form-label">
        <label>{{libraryEnum.label5}}</label>
      </div>
      <input
          :class="{' border-2 border-red-600':  isError && !file}"
          class="form-input" type="file" ref="refFileWav" >
    </div>

    <div class="form-content-field">
      <p class=" text-center text-red-600" v-if="isError"
      > <Icon :name="Icons.IconOutlineError" cursor-pointer /> {{ error }}</p>

    </div>

    <div class="form-button-container">
      <button v-if="!isNotCompatible" class="form-button" :disabled="disabled" type="submit" >{{libraryEnum.submit2}}</button>
      <p v-if="isNotCompatible" class="form-button form-button-converting"  @click="handleConverting()">Convertir le fichier au bon format</p>
    </div>
  </form>
</template>
