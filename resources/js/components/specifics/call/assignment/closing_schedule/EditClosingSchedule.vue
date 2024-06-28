<script lang="ts">
import {useStore} from "vuex";
import {UAssignment} from "../../../../../utils/UAssignment";
import Icon from "../../../../generics/Icon/Icon.vue"
import {Icons} from "../../../../generics/Icon/Icons";
import {computed, onMounted, ref, Ref} from "vue";
import {PlanningType} from "../../../../../types/call/planningType";
import {Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions} from "@headlessui/vue";
import {Assignment} from "../../../../../enums/Assignment";
import {AssignmentForAllType} from "../../../../../types/call/AssignmentType";
export default {
    name: Assignment.EditClosingSchedule,
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
  setup(){
    const store = useStore()
    const currentAssignmentPlanning = computed(() => store.getters.findByIdAssignmentPlanning)
    const findAllAssignmentForAlls: Ref<AssignmentForAllType[]> = computed(() => store.getters.findAllAssignmentForAll)
    let isError: Ref<boolean> = ref(false)
    let error: Ref<string> = ref("")
    let newAssignmentPlanning: Ref<PlanningType> = ref(Object.assign({}, currentAssignmentPlanning.value))

    function editAssignment (event: any) {
      event.preventDefault();
        newAssignmentPlanning.value.id_message = UAssignment.messageSelectedId(newAssignmentPlanning.value.nom, findAllAssignmentForAlls.value)
      store.dispatch(Assignment.updateAssignmentPlanning, {data: newAssignmentPlanning.value, action: Assignment.SCHEDULE}).then(async (response) => {
        if (response.ok && response.status === 200) {
            const jsonResponse = await response.json();
          store.commit(Assignment.openAlert, Assignment.message)
          store.commit(Assignment.closeModal)
          setTimeout(() => {
            store.commit(Assignment.closeAlert)
          }, 3000)
        }
      })
    }
    onMounted(() => {
      store.dispatch(Assignment.findAllAssignmentForAllAction)
    })
    return {
      error,
      isError,
      newAssignmentPlanning,
        findAllAssignmentForAlls,
      editAssignment,
      UAssignment,
      assignment: Assignment
    }
  }
}
</script>
<template>
  <form class="form" @submit="editAssignment">
    <h2 class="form-title">{{newAssignmentPlanning.nom_planning}}</h2>
    <div class="form-content-field">
      <div class="form-label">
        <label>{{assignment.label3planning}}</label>
      </div>
      <Listbox as="div" v-model="newAssignmentPlanning.nom">
        <div class="seleclect-main">
          <ListboxButton class="seleclect-button">
            <span>{{ UAssignment.messageValueTrue(newAssignmentPlanning.nom)}}</span>
          </ListboxButton>
          <transition leave-active-class="transition-active" leave-from-class="transition-from" leave-to-class="transition-to">
            <ListboxOptions class="seleclect-list-option">
              <ListboxOption v-for="(findAllAssignmentForAll) in findAllAssignmentForAlls" :key="findAllAssignmentForAll.id_message" :value="findAllAssignmentForAll.nom" v-slot="{ active, selected }">
                <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                  <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">
                    {{ findAllAssignmentForAll.nom }}

                  </p>
                    <Icon v-if="selected" :name="Icons.IconBaselineCheck" :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                </li>
              </ListboxOption>
            </ListboxOptions>
          </transition>
        </div>
      </Listbox>
    </div>
    <div class="form-button-container">
      <button class="form-button" :disabled="false" type="submit" >{{assignment.submit1}}</button>
    </div>
  </form>
</template>
