<script lang="ts">
import {Icons} from "../../../generics/Icon/Icons";
import Icon from "../../../generics/Icon/Icon.vue"
import {useStore} from "vuex";
import {ref, Ref} from "vue";
import {LibraryEnum} from "../../../../enums/Library";
import {UCookies} from "../../../../utils/UCookies";

interface FormInput {
  name: string;
  fileWAV: string;
}
export default {
  name: LibraryEnum.addLibraryWAV,
  components: {
    Icon
  },
  setup() {
    const store = useStore()
    const formInput: Ref<FormInput> = ref({
      name: '',
      fileWAV: ""
    })
    const refFileWav = ref(null);
    let convertingFile: Ref<boolean> = ref(false)
    let isError: Ref<boolean> = ref(false)
    let isNotCompatible: Ref<boolean> = ref(false)
    let error: Ref<string> = ref("")
      let sourceDestination: Ref<string> = ref("")
        let disabled: Ref<boolean>= ref(false)


    function isWavFile(file: any){
      const mimeType = file.type;
      return mimeType === LibraryEnum.typeWav;
    }
    function addLibrary (event: any) {
      event.preventDefault();
        const file = refFileWav.value?.files[0];
      if (!formInput.value.name || !file) {
        isError.value = true
        error.value = LibraryEnum.messageError1
      }
      else if(!isWavFile(file)) {
        isError.value = true
        error.value = LibraryEnum.messageErrorTypeWav
      }
      else {
        let formData = new FormData();
        formData.append(LibraryEnum.nom_personnalise, formInput.value.name)
        formData.append(LibraryEnum.path_banque_message, file)
        formData.append(LibraryEnum.id_type_diffusion, '1')
        formData.append(LibraryEnum.id_langue, null)
        formData.append(LibraryEnum.type, LibraryEnum.WAV)
        formData.append(LibraryEnum.directoryMedia, UCookies.getCookie("media_directory"))


         store.dispatch(LibraryEnum.addFileLibraryAction, formData).then((response) => {
             if(response.status === 409) {
                 isError.value = true
                 error.value = response.message
             }
          if(response.status === 422) {
              isNotCompatible.value = true
            isError.value = true
            error.value = response.message
          }
          if(response.ok && response.status === 200) {
            store.commit(LibraryEnum.openAlert, LibraryEnum.messageAdd )
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
          const sourceDestinationConv = `${formInput.value.name}.wav`
          let formData = new FormData();
          formData.append(LibraryEnum.nom_personnalise, formInput.value.name)
          formData.append(LibraryEnum.path_banque_message, file)
          formData.append("source_destination", sourceDestinationConv)
          formData.append(LibraryEnum.id_type_diffusion, '1')
          formData.append(LibraryEnum.id_langue, null)
          formData.append(LibraryEnum.type, LibraryEnum.WAV)
          formData.append(LibraryEnum.directoryMedia, UCookies.getCookie("media_directory"))
          store.dispatch("convertingFileFileLibraryAction", formData).then((response) => {
              disabled.value = true
              if(response === 500) {
                  isError.value = true
                  error.value = "Une erreur s'est produite lors de la conversion."
                  setTimeout(()=> {
                      store.commit(LibraryEnum.closeModal)
                  },3000)
              }
              if(response.ok && response.status === 200) {
                  store.commit(LibraryEnum.openAlert, LibraryEnum.messageAdd )
                  store.commit(LibraryEnum.closeModal)
                  setTimeout(()=> {
                      store.commit(LibraryEnum.closeAlert)
                  },3000)
              }
          })
        }
    return {
      addLibrary,
      refFileWav,
      convertingFile,
      error,
      isNotCompatible,
      handleConverting,
      isError,
      formInput,
      libraryEnum: LibraryEnum,
      disabled
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
  <form  class="form" enctype="multipart/form-data" @submit="addLibrary" method="post">
    <div class="form-content-field">
      <div class="form-label">
        <label>{{ libraryEnum.label1 }}</label>
      </div>
      <input
          v-model="formInput.name"
          :class="{' border-2 border-red-600':  isError && !formInput.name}"
          class="form-input" type="text"
      />
    </div>

    <div class="form-content-field-">
      <div class="form-label">
        <label>{{ libraryEnum.label2 }}</label>
      </div>
      <p>{{ libraryEnum.p2 }} <Icon  :name="Icons.IconOutlineAudioFile" cursor-pointer class="icon"/></p>
    </div>


    <div class="form-content-field">
      <div class="form-label">
        <label>{{libraryEnum.label5}}</label>
      </div>
      <input
          :class="{' border-2 border-red-600':  isError && !formInput.fileWAV}"
          class="form-input" type="file" ref="refFileWav" >
    </div>

    <div class="form-content-field">
      <p class=" text-center text-red-600 mb-1" v-if="isError"
      > <Icon  :name="Icons.IconOutlineError" cursor-pointer /> {{ error }}</p>
        <p v-if="isNotCompatible" class="form-button form-button-converting"  @click="handleConverting()">Convertir le fichier au bon format</p>
    </div>

    <div class="form-button-container">
      <button v-if="!isNotCompatible" class="form-button" :disabled="disabled" type="submit" >{{libraryEnum.submit1}}</button>
    </div>
  </form>
</template>
<style scoped>

</style>
