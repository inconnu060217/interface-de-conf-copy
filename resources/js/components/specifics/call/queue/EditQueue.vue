<script lang="ts">
import {computed, onMounted, ref, Ref} from "vue";
import {useStore} from "vuex";
import {QueueCallWebServiceType, QueueCallType} from "../../../../types/call/QueueCallType.js";
import {PlanningWebServiceType} from "../../../../types/call/planningType";
import {OverflowType} from "../../../../types/call/OverflowType";
import {Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions} from "@headlessui/vue";
import {Icons} from "../../../generics/Icon/Icons";
import Icon from "../../../generics/Icon/Icon.vue"
import {EnumQueue} from "../../../../enums/Queue";
import {UQueue} from "../../../../utils/UQueue";
import {AssignmentType} from "../../../../types/call/AssignmentType";
export default {
  name: EnumQueue.nameEdit,
  components: {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
    Icon
  },
  setup() {
    const store = useStore()
      let disabled: Ref<boolean>= ref(false)
    const queueCallCurrent: Ref<QueueCallType> = computed(() => store.getters.findByIdQueueCall)
      const typeTeterrences: Ref<AssignmentType[]> = computed(() => store.getters.findAllAssignmentTypeTeterrences)
    const overflowTypes: Ref<OverflowType[]> = computed(() => store.getters.findOverflowType)
    const queueCallWebServices: Ref<QueueCallWebServiceType[]> = computed(()=> store.getters.findQueueCallWebServe)
    const planningWebServices: Ref<PlanningWebServiceType[]> = computed(()=> store.getters.findPlanningWebService)
    const newQueueCall: Ref<QueueCallType> = ref(Object.assign({}, queueCallCurrent.value))
    newQueueCall.value.callback_attente = UQueue.activationCallbackTrue(newQueueCall.value.callback_attente)
    function editQueueCall(event: any) {
      event.preventDefault();
      newQueueCall.value.kid_file_attente_debordement = UQueue.queueOverflowId(newQueueCall.value.File_attente_debordement, queueCallWebServices.value)
      newQueueCall.value.kid_planning_fa = UQueue.planningIdSelected(newQueueCall.value.nom_fa_planning, planningWebServices.value)
      newQueueCall.value.id_type_debordement = UQueue.overflowTypeId(newQueueCall.value.nom_debordement, overflowTypes.value)
      newQueueCall.value.callback_attente = UQueue.activationCallBack(newQueueCall.value.callback_attente)
        newQueueCall.value.id_debordement_message = UQueue.overflowMessageId(newQueueCall.value.nom_debordement_message, typeTeterrences.value)
      store.dispatch(EnumQueue.updateQueueCallAction, newQueueCall.value).then(async (response) => {
          disabled.value = true
        if(response.ok && response.status === 200) {
            const jsonResponse = await response.json()
          store.commit(EnumQueue.openAlert, jsonResponse.message, response.status )
          store.commit(EnumQueue.closeModal)
            disabled.value = false
          setTimeout(()=> {
            store.commit(EnumQueue.closeAlert)
          },3000)
        }
      })
    }
    onMounted(() => {
      store.dispatch(EnumQueue.findAllWebserviceAction)
      store.dispatch(EnumQueue.findPlanningWebServiceAction)
      store.dispatch(EnumQueue.findOverflowType)
      store.dispatch(EnumQueue.findAllAssignmentTypeTeterrencesAction)
    })
    return {
      editQueueCall,
      newQueueCall,
      planningWebServices,
      queueCallWebServices,
      overflowTypes,
        disabled,
        typeTeterrences,
      queueCallCurrent,
      enumQueue: EnumQueue,
      UQueue
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
  <form class="form" @submit="editQueueCall">
    <h2 class="form-title">{{newQueueCall.nom_file_attente}}</h2>
    <div class="form-content-field">
      <div class="form-label">
        <label>{{enumQueue.label2}}</label>
      </div>
      <div>
        <Listbox as="div" v-model="newQueueCall.nom_fa_planning">
          <div class="seleclect-main">
            <ListboxButton class="seleclect-button">
              <span>{{ UQueue.checkPlanningTrue(newQueueCall.nom_fa_planning) }}</span>
            </ListboxButton>
            <transition leave-active-class="transition-active" leave-from-class="transition-from" leave-to-class="transition-to">
              <ListboxOptions class="seleclect-list-option">
                <ListboxOption :value="0" v-slot="{ active, selected }">
                  <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                    <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">Aucun</p>
                    <div class="flex justify-center items-center">
                        <Icon v-if="selected" :name="Icons.IconBaselineCheck"  :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                    </div>
                  </li>
                </ListboxOption>
                <ListboxOption v-for="(planningWebService) in planningWebServices" :key="planningWebService.id" :value="planningWebService.name" v-slot="{ active, selected }">
                  <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                    <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">{{ planningWebService.name }}</p>
                      <Icon v-if="selected" :name="Icons.IconBaselineCheck"  :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                  </li>
                </ListboxOption>
              </ListboxOptions>
            </transition>
          </div>
        </Listbox>
      </div>
    </div>

    <div class="form-content-field">
      <div class="form-label">
        <label>{{enumQueue.label3}}</label>
      </div>
      <input v-model="newQueueCall.duree_dissuasion_tae" class="form-input" type="text" />
    </div>

    <div class="form-content-field">
      <div class="form-label">
        <label>{{enumQueue.label4}}</label>
      </div>
      <input v-model="newQueueCall.duree_dissuasion_reel" class="form-input" type="text" />
    </div>

    <div class="form-content-field">
      <div class="form-label">
        <label>{{enumQueue.label5}}</label>
      </div>
      <input v-model="newQueueCall.ratio_agent_attente" class="form-input" type="text" />
    </div>

    <div class="form-content-field">
      <div class="form-label">
        <label>{{enumQueue.label6}}</label>
      </div>
      <div>
          <Listbox as="div" v-model="newQueueCall.nom_debordement">
            <div class="seleclect-main">
              <ListboxButton class="seleclect-button">
                <span>{{ newQueueCall.nom_debordement }}</span>
              </ListboxButton>
              <transition leave-active-class="transition-active" leave-from-class="transition-from" leave-to-class="transition-to">
                <ListboxOptions class="seleclect-list-option">
                  <ListboxOption v-for="(overflowType) in overflowTypes" :key="overflowType.id_debordement" :value="overflowType.nom" v-slot="{ active, selected }">
                    <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                      <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">{{ overflowType.nom }}</p>
                        <Icon v-if="selected" :name="Icons.IconBaselineCheck"  :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                    </li>
                  </ListboxOption>
                </ListboxOptions>
              </transition>
            </div>
          </Listbox>
        </div>
    </div>

    <div :class="(newQueueCall.nom_debordement === enumQueue.renvoi ? 'form-content-field ' :'form-content-field-edit ')">
      <div class="form-label">
        <label>{{enumQueue.label7}}</label>
      </div>
      <input v-if="newQueueCall.nom_debordement === enumQueue.renvoi" v-model="newQueueCall.renvoi" class="form-input" type="text" />
      <div v-else class="flex justify-center align-center">
        <Icon  :name="Icons.IconCheckIndeterminateSmall" cursor-pointer class="icon-black"/>
      </div>
    </div>

    <div :class="(newQueueCall.nom_debordement === enumQueue.fileAttente ? 'form-content-field ' :'form-content-field-edit ')">
      <div class="form-label">
        <label>{{enumQueue.label8}}</label>
      </div>
        <Listbox v-if="newQueueCall.nom_debordement === enumQueue.fileAttente" as="div" v-model="newQueueCall.File_attente_debordement">
          <div class="seleclect-main">
            <ListboxButton class="seleclect-button">
              <span>{{ UQueue.queueOverflowCkeckValue(newQueueCall.File_attente_debordement)}}</span>
            </ListboxButton>
            <transition leave-active-class="transition-active" leave-from-class="transition-from" leave-to-class="transition-to">
              <ListboxOptions class="seleclect-list-option">
                  <ListboxOption :value="0" v-slot="{ active, selected }">
                      <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                          <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">Aucun</p>
                          <div class="flex justify-center items-center">
                              <Icon v-if="selected" :name="Icons.IconBaselineCheck" :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                          </div>
                      </li>
                  </ListboxOption>
                <ListboxOption
                    v-if="UQueue.exceptQueueOverflow(queueCallCurrent.nom_file_attente, queueCallWebServices) !== false"
                    v-for="(queueCallWebService) in UQueue.exceptQueueOverflow(queueCallCurrent.nom_file_attente, queueCallWebServices)"
                    :key="queueCallWebService.serviceId"
                    :value="queueCallWebService.serviceName"
                    v-slot="{ active, selected }"
                >
                  <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                    <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">{{ queueCallWebService.serviceName }}</p>
                      <Icon v-if="selected" :name="Icons.IconBaselineCheck"  :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                  </li>
                </ListboxOption>
              </ListboxOptions>
            </transition>
          </div>
        </Listbox>
      <div v-else class="flex justify-center align-center">
        <Icon  :name="Icons.IconCheckIndeterminateSmall" cursor-pointer class="icon-black"/>
      </div>
    </div>

      <div :class="(newQueueCall.nom_debordement === enumQueue.messageDbedordement ? 'form-content-field ' :'form-content-field-edit ')">
          <div class="form-label">
              <label>{{enumQueue.label9}}</label>
          </div>
              <Listbox as="div" v-if="newQueueCall.nom_debordement === enumQueue.messageDbedordement" v-model="newQueueCall.nom_debordement_message">
                  <div class="seleclect-main">
                      <ListboxButton class="seleclect-button">
                          <span>{{ UQueue.queueOverflowCkeckValue(newQueueCall.nom_debordement_message) }}</span>
                      </ListboxButton>
                      <transition leave-active-class="transition-active" leave-from-class="transition-from" leave-to-class="transition-to">
                          <ListboxOptions class="seleclect-list-option">
                              <ListboxOption v-for="(typeTeterrence) in typeTeterrences" :key="typeTeterrence.id_message" :value="typeTeterrence.nom" v-slot="{ active, selected }">
                                  <li :class="[active ? 'seleclect-li-active' : 'seleclect-li-no-active', 'seleclect-li']">
                                      <p :class="[selected ? 'seleclect-li-p-selected' : 'seleclect-li-p-no-selected', 'seleclect-li-p']">{{ typeTeterrence.nom }}</p>
                                      <Icon v-if="selected" :name="Icons.IconBaselineCheck"  :class="[active ? 'select-icon-active' : 'select-icon-no-active', 'select-icon']"/>
                                  </li>
                              </ListboxOption>
                          </ListboxOptions>
                      </transition>
                  </div>
              </Listbox>
          <div v-else class="flex justify-center align-center">
              <Icon  :name="Icons.IconCheckIndeterminateSmall" cursor-pointer class="icon-black"/>
          </div>
      </div>

    <div class="form-content-field-edit">
      <div class="form-label">
        <label>{{enumQueue.label10}}</label>
      </div>
      <div class="flex justify-center align-center">
        <input
            type="checkbox"
            v-model="newQueueCall.callback_attente"
            class="form-check boxchecked:bg-[#9f4c6f] focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 text-[#9f4c6f]"
        />
      </div>
    </div>

    <div class="form-content-field">
      <div class="form-label">
        <label>{{enumQueue.label11}}</label>
      </div>
      <input v-model="newQueueCall.propriete" class="form-input" type="text" />
    </div>

      <div class="form-content-field">
          <div class="form-label">
              <label>{{enumQueue.label12}}</label>
          </div>
          <div class="form-field">
              <input
                  :class="{' border-2 border-red-600': !newQueueCall.numero_presente}"
                  id="routage"
                  v-model="newQueueCall.numero_presente"
                  type="text"
                  class="form-input"
              />
          </div>
      </div>

      <div class="form-content-field">
          <div class="form-label">
              <label>{{enumQueue.label13}}</label>
          </div>
          <div class="form-field">
              <input
                  :class="{' border-2 border-red-600': !newQueueCall.temps_post_appel}"
                  id="routage"
                  v-model="newQueueCall.temps_post_appel"
                  type="text"
                  class="form-input"
              />
          </div>
      </div>

      <div class="form-content-field">
          <div class="form-label">
              <label>{{enumQueue.label14}}</label>
          </div>
          <div class="form-field">
              <input
                  :class="{' border-2 border-red-600': !newQueueCall.callback_annonce_time}"
                  id="routage"
                  v-model="newQueueCall.callback_annonce_time"
                  type="text"
                  class="form-input"
              />
          </div>
      </div>


    <div class="form-button-container">
      <button class="form-button" :disabled="disabled" type="submit" >{{enumQueue.submit}}</button>
    </div>
  </form>
</template>
<style scoped>

</style>
