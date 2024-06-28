<script lang="ts">
import {useStore} from "vuex";
import {computed, ref, Ref, watch} from "vue";
import Icon from "../../../generics/Icon/Icon.vue";
import {Icons} from "../../../generics/Icon/Icons";
import {Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions} from "@headlessui/vue";

interface FormInput {
    text: string,
    name_file: string,
    voice: string,
    gender: string,
    language: string,
    speaking_rate: number,
    name_file_default: string,
}
export default {
    name: "AddTextToSpeech",
    components: {
        Icon,
        Listbox,
        ListboxButton,
        ListboxLabel,
        ListboxOption,
        ListboxOptions,
    },
    computed: {
        Icons() {
            return Icons
        }
    },
    setup() {
        const store = useStore()
        const dataOptionVoice = ["fr-FR-Standard-A"]
        const dataOptionGender = ["male", "female"]
        const dataOptionLanguage = ["fr"]
        const formInput: Ref<FormInput> = ref({
            text: '',
            name_file: "",
            name_file_default: "",
            voice: dataOptionVoice[0],
            gender: dataOptionGender[0],
            language: dataOptionLanguage[0],
            speaking_rate: 1,
        })
        let isError: Ref<boolean> = ref(false)
        let error: Ref<string> = ref("")
        let disabled: Ref<boolean> = ref(false)

        function validateSpeakingRate(value: any) {
            const speakingRate = parseFloat(value);
            return speakingRate >= 0 && speakingRate <= 1;
        }

        function addTextToSpeech() {

            if(!formInput.value.text || !formInput.value.name_file_default) {
                isError.value = true
                error.value = "Tous les champs doivent être remplir."
            }
            else if(!validateSpeakingRate(formInput.value.speaking_rate)) {
                isError.value = true
                error.value = "Le débit doit être 0 à 1."
            }
            else {
                formInput.value.name_file = `${formInput.value.name_file_default}.wav`
                store.dispatch("addTextToSpeechAction", formInput.value).then((response) => {
                    if(response.status === 409) {
                        isError.value = true
                        error.value = response.message
                    }
                    if(response.status === 422) {
                        isError.value = true
                        error.value = response.message
                        setTimeout(()=> {
                            store.commit("closeModal")
                        },3000)
                    }
                    if(response.ok && response.status === 201) {
                        store.commit("openAlert", "synthése vocale a bien été généré avec succès.")
                        store.commit("closeModal")
                        setTimeout(()=> {
                            store.commit("closeAlert")
                        },3000)
                    }
                })
            }
        }
        return {
            isError,
            error,
            disabled,
            dataOptionVoice,
            dataOptionGender,
            dataOptionLanguage,
            formInput,
            addTextToSpeech
        }

    }
}
</script>
<template>
    <div  class="form" >
        <div class="form-content-field">
            <div class="form-label">
                <label>Contenu :</label>
            </div>
            <textarea
                :class="{' border-2 border-red-600':  isError && !formInput.text}"
                v-model="formInput.text"
                class="form-input">
      </textarea>
        </div>
        <div class="form-content-field">
            <div class="form-label">
                <label>Nom du fichier générer :</label>
            </div>
            <input
                v-model="formInput.name_file_default"
                :class="{' border-2 border-red-600':  isError && !formInput.name_file_default}"
                class="form-input" type="text"
            />
        </div>
        <div class="form-content-field">
            <div class="form-label">
                <label>Genre :</label>
            </div>
            <Listbox as="div" v-model="formInput.gender">
                <div class="seleclect-main">
                    <ListboxButton class="seleclect-button">
                        <span>{{ formInput.gender}}</span>
                    </ListboxButton>
                    <transition leave-active-class="transition-active" leave-from-class="transition-from" leave-to-class="transition-to">
                        <ListboxOptions class="seleclect-list-option">
                            <ListboxOption v-for="(value, index) in dataOptionGender" :key="index" :value="value" v-slot="{ active, selected }">
                                <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                                    <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">{{ value }}</p>
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
                <label>Genre :</label>
            </div>
            <Listbox as="div" v-model="formInput.language">
                <div class="seleclect-main">
                    <ListboxButton class="seleclect-button">
                        <span>{{ formInput.language}}</span>
                    </ListboxButton>
                    <transition leave-active-class="transition-active" leave-from-class="transition-from" leave-to-class="transition-to">
                        <ListboxOptions class="seleclect-list-option">
                            <ListboxOption v-for="(value, index) in dataOptionLanguage" :key="index" :value="value" v-slot="{ active, selected }">
                                <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                                    <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">{{ value }}</p>
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
                <label>Voix :</label>
            </div>
            <Listbox as="div" v-model="formInput.voice">
                <div class="seleclect-main">
                    <ListboxButton class="seleclect-button">
                        <span>{{ formInput.voice}}</span>
                    </ListboxButton>
                    <transition leave-active-class="transition-active" leave-from-class="transition-from" leave-to-class="transition-to">
                        <ListboxOptions class="seleclect-list-option">
                            <ListboxOption v-for="(value, index) in dataOptionVoice" :key="index" :value="value" v-slot="{ active, selected }">
                                <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                                    <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">{{ value }}</p>
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
                <label>Débit (0 à 1) :</label>
            </div>
            <input
                v-model="formInput.speaking_rate"
                :class="{' border-2 border-red-600':  isError && !formInput.speaking_rate}"
                class="form-input" type="number" step="0.01" min="0" max="1"
            />
        </div>
        <div class="form-content-field" v-if="isError">
            <p class=" text-center text-red-600" > <Icon  :name="Icons.IconOutlineError" /> {{ error }}</p>
        </div>
        <div class="form-button-container">
            <button class="form-button"  @click="addTextToSpeech" :disabled="false" type="submit" >Ajouter</button>
        </div>
    </div>
</template>
