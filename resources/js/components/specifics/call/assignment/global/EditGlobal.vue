<script lang="ts">
import {useStore} from "vuex";
import Icon from "../../../../generics/Icon/Icon.vue"
import {Icons} from "../../../../generics/Icon/Icons";
import {computed, onMounted, ref, Ref} from "vue";
import {AssignmentType} from "../../../../../types/call/AssignmentType";
import {LibraryEnum} from "../../../../../enums/Library";
import {UAssignment} from "../../../../../utils/UAssignment";
import {Assignment} from "../../../../../enums/Assignment";
import {ULibrary} from "../../../../../utils/ULibrary";
import {Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions} from "@headlessui/vue";
export default {
    name: Assignment.EditGlobal,
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
        const currentAssignmentGlobal = computed(() => store.getters.findByIdAssignment)
        const libraries = computed(() => store.getters.findAllLibrary)
        let diffusionTypes = computed(()=> store.getters.findAllDiffusionType)
        let isError: Ref<boolean> = ref(false)
        let error: Ref<string> = ref("")
        let previousLibrary : Ref<string> = ref(currentAssignmentGlobal.value.nom_personnalise)
        let newAssignmentGlobal: Ref<AssignmentType> = ref(Object.assign({}, currentAssignmentGlobal.value))
        newAssignmentGlobal.value.activation = UAssignment.activationTrue(newAssignmentGlobal.value.activation)
        function editAssignment (event: any) {
            event.preventDefault();
            newAssignmentGlobal.value.activation = UAssignment.activationNumber(newAssignmentGlobal.value.activation)
            newAssignmentGlobal.value.activation = UAssignment.activationTrue(newAssignmentGlobal.value.activation)

            let result = UAssignment.librarySelected(newAssignmentGlobal.value.nom_personnalise, libraries.value)

          if (result === 0){
                newAssignmentGlobal.value.id_banque_message = result
                newAssignmentGlobal.value.id_type_diffusion = null
                newAssignmentGlobal.value.activation = UAssignment.activationNumber(newAssignmentGlobal.value.activation)
            } else {
                newAssignmentGlobal.value.id_type_diffusion = result.id_type_diffusion
                newAssignmentGlobal.value.id_banque_message = result.id_banque_message
                newAssignmentGlobal.value.contenu_tts = result.contenu_tts
                newAssignmentGlobal.value.path_banque_message = result.path_banque_message
                newAssignmentGlobal.value.activation = UAssignment.activationNumber(newAssignmentGlobal.value.activation)

            }
          ULibrary.updateLibraryMutation(store,newAssignmentGlobal.value.nom_personnalise, previousLibrary.value, newAssignmentGlobal.value.nom, newAssignmentGlobal.value.id_categorie_message)
            store.dispatch(Assignment.updateAssignment, {data: newAssignmentGlobal.value, action: Assignment.GLOBAL}).then((response) => {
                if(response.ok && response.status === 200) {
                    store.commit(Assignment.openAlert, Assignment.message)
                    store.commit(Assignment.closeModal)
                    setTimeout(()=> {
                        store.commit(Assignment.closeAlert)
                    },3000)
                }
            })
        }

        onMounted(() => {
            store.dispatch(Assignment.findAllDiffusionTypeAction)
            store.dispatch(Assignment.findAllLibraryAction)
        })
        return {
          error,
          isError,
          newAssignmentGlobal,
          LibraryEnum,
          libraries,
          diffusionTypes,
          editAssignment,
          UAssignment,
          assignment: Assignment
        }
    }
}
</script>
<template>
    <form class="form" @submit="editAssignment">
        <h2 class="form-title">{{newAssignmentGlobal.nom}}</h2>
        <div class="form-content-field-">
            <div class="form-label">
                <label>{{assignment.label2}}</label>
            </div>
            <p>
                {{ UAssignment.DiffusionType(newAssignmentGlobal.id_type_diffusion, diffusionTypes) }}
                <Icon v-if="UAssignment.DiffusionType(newAssignmentGlobal.id_type_diffusion, diffusionTypes) === LibraryEnum.VocalSynthesis" :name="Icons.IconOutlineHeadsetMic" cursor-pointer class="icon"/>
                <Icon v-if="UAssignment.DiffusionType(newAssignmentGlobal.id_type_diffusion, diffusionTypes) === LibraryEnum.AudioMessage" :name="Icons.IconOutlineAudioFile" cursor-pointer class="icon"/>
            </p>
        </div>
        <div class="form-content-field">
            <div class="form-label">
                <label>{{assignment.label3}}</label>
            </div>
            <Listbox as="div" v-model="newAssignmentGlobal.nom_personnalise">
                <div class="seleclect-main">
                    <ListboxButton class="seleclect-button">
                        <span>{{ UAssignment.labraryValueTrue(newAssignmentGlobal.nom_personnalise)}}</span>
                    </ListboxButton>
                    <transition leave-active-class="transition-active" leave-from-class="transition-from" leave-to-class="transition-to">
                        <ListboxOptions class="seleclect-list-option">
                            <ListboxOption :value="0" v-slot="{ active, selected }">
                                <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                                    <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">Aucun</p>
                                    <Icon v-if="selected" :name="Icons.IconBaselineCheck"  :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                                </li>
                            </ListboxOption>
                            <ListboxOption v-for="(librarie) in libraries" :key="librarie.id_banque_message" :value="librarie.nom_personnalise" v-slot="{ active, selected }">
                                <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                                    <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">
                                      {{ librarie.nom_personnalise }}
                                      <Icon v-if="librarie.nom_diffusion === LibraryEnum.VocalSynthesis" :name="Icons.IconOutlineHeadsetMic" :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                                      <Icon v-if="librarie.nom_diffusion === LibraryEnum.AudioMessage" :name="Icons.IconOutlineAudioFile"  :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                                    </p>
                                  <Icon v-if="selected" :name="Icons.IconBaselineCheck" :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                                </li>
                            </ListboxOption>
                        </ListboxOptions>
                    </transition>
                </div>
            </Listbox>
        </div>
        <div class="form-content-field-">
            <div class="form-label">
                <label>{{assignment.label4}}</label>
            </div>
            <input
                type="checkbox"
                v-model="newAssignmentGlobal.activation"
                :disabled="newAssignmentGlobal.activable === 0"
                :class="[newAssignmentGlobal.activable === 0 ? 'opacity-50' : '']"
                class=" mr-2 boxchecked:bg-primary block focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 text-primary"
            />
        </div>
        <div class="form-button-container">
            <button class="form-button" :disabled="false" type="submit" >{{assignment.submit1}}</button>
        </div>
    </form>
</template>
