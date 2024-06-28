<script lang="ts">
import {Icons} from "../../../generics/Icon/Icons";
import Icon from "../../../generics/Icon/Icon.vue"
import {Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions} from "@headlessui/vue";
import {ref, Ref} from "vue";
import {useStore} from "vuex";
import {LibraryEnum} from "../../../../enums/Library";
interface FormInput {
  name: string;
  langue: string;
  contentTTS: string;
}
export default {
  name: LibraryEnum.AddLibraryTTS,
  components: {
    Icon,
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
  },

  setup() {
    const store = useStore()
    const langues = ref([{id: 1, nom: "Français"},{id: 2, nom: "Anglais"}])
    const formInput: Ref<FormInput> = ref({
      name: '',
      langue: "Français",
      contentTTS: ""
    })
    let isError: Ref<boolean> = ref(false)
    let error: Ref<string> = ref("")
    function addLibrary (event: any) {
      event.preventDefault();
      if(formInput.value.name === "" || formInput.value.contentTTS === "") {
        isError.value = true
        error.value = LibraryEnum.messageError1
      } else {
        const selectedLangue = langues.value.find((langue: any) => langue.nom === formInput.value.langue)?.id;
        let dataSend = {
          nom_personnalise: formInput.value.name,
          contenu_tts: formInput.value.contentTTS,
          id_type_diffusion: 2,
          id_langue: selectedLangue,
          type: "TTS"
        }
        store.dispatch(LibraryEnum.addLibraryAction, dataSend).then((response) => {
          if(response.status === 409) {
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

    return {
      addLibrary,
      langues,
      error,
      isError,
      formInput,
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
  <form class="form" @submit="addLibrary">
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
      <p>{{ libraryEnum.p1 }} <Icon  :name="Icons.IconOutlineHeadsetMic"  class="icon"/></p>
    </div>

    <div class="form-content-field">
      <div class="form-label">
        <label>{{ libraryEnum.label3 }}</label>
      </div>
      <Listbox v-model="formInput.langue" >
        <div class="seleclect-main">
          <ListboxButton class="seleclect-button">
            <span>{{ formInput.langue}}</span>
          </ListboxButton>
          <transition leave-active-class="transition-active" leave-from-class="transition-from" leave-to-class="transition-to">
            <ListboxOptions class="seleclect-list-option">
              <ListboxOption v-for="value in langues" :key="value.id" :value="value.nom" v-slot="{ active, selected }">
                <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                  <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">{{ value.nom }}</p>
                    <Icon v-if="selected" :name="Icons.IconBaselineCheck"  :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                </li>
              </ListboxOption>
            </ListboxOptions>
          </transition>
        </div>
      </Listbox>
    </div>

    <div class="form-content-field">
      <div class="form-label">
        <label>{{ libraryEnum.label4 }}</label>
      </div>
      <textarea
          :class="{' border-2 border-red-600':  isError && !formInput.contentTTS}"
          v-model="formInput.contentTTS"
          class="form-input">
      </textarea>
    </div>

    <div class="form-content-field">
      <p class=" text-center text-red-600" v-if="isError"
      > <Icon  :name="Icons.IconOutlineError" /> {{ error }}</p>
    </div>

    <div class="form-button-container">
      <button class="form-button" :disabled="false" type="submit" >{{ libraryEnum.submit1 }}</button>
    </div>
  </form>
</template>
<style scoped>

</style>
