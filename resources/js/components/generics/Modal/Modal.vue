<script lang="ts" setup>
import {Components} from "@/constants/Components";
import {Icons} from "../Icon/Icons.ts";
import Icon from "../Icon/Icon.vue"
import EditEntryPoint from "@/components/specifics/call/entryPoint/EditEntryPoint.vue";
import {useStore} from "vuex";
import {computed} from "vue";
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { CheckIcon } from '@heroicons/vue/24/outline'
import {ActionStore} from "@/constants/ActionStore";
/*import EditQueueCall from "../../specifics/call/queue/EditQueue.vue";
import EditQueueEmail from "../../specifics/email/queue/EditQueue.vue";
import EditReceiptOfEmail from "../../specifics/email/acknowledgements_of_receip/receipt_of_email/EditReceiptOfEmaile.vue";
import AddException from "../../specifics/email/acknowledgements_of_receip/exceptions/AddException.vue";
import EditException from "../../specifics/email/acknowledgements_of_receip/exceptions/EditException.vue";
import DeleteException from "../../specifics/email/acknowledgements_of_receip/exceptions/DeleteException.vue";
import EditLibraryTTS from "../../specifics/call/library/EditLibraryTTS.vue";
import EditLibraryWAV from "../../specifics/call/library/EditLibraryWAV.vue";
import AddLibraryTTS from "../../specifics/call/library/AddLibraryTTS.vue";
import AddLibraryWAV from "../../specifics/call/library/AddLibraryWAV.vue";
import DeleteLibraryWAV from "../../specifics/call/library/DeleteLibraryWAV.vue";
import DeleteLibraryTTS from "../../specifics/call/library/DeleteLibraryTTS.vue";
import EditGlobal from "../../specifics/call/assignment/global/EditGlobal.vue";
import EditClosingSchedule from "../../specifics/call/assignment/closing_schedule/EditClosingSchedule.vue";
import EditExceptionalClosure from "../../specifics/call/assignment/exceptional_closure/EditExceptionalClosure.vue";
import EditContentEditor from "../../specifics/email/content_editor/EditContentEditor.vue";
import AddTextToSpeechs from "../../specifics/call/text_to_speech/AddTextToSpeechs.vue";
import DeleteTextToSpeechs from "../../specifics/call/text_to_speech/DeleteTextToSpeechs.vue";
import AddRuleEmail from "../../specifics/email/routing_rule/AddRuleEmail.vue";
import editRuleEmail from "../../specifics/email/routing_rule/EditRuleEmail.vue";
import Visualize from "../../specifics/email/routing_rule/Visualize.vue";*/


const store = useStore();
const isOpenModal = computed(() => store.getters.isOpenModal);
const componentNameModal = computed(() => store.getters.componentNameModal);

const createComponent = () => {
    let comp: any;
    let componentName = componentNameModal.value;
    switch (componentName) {
      case Components.EDIT_ENTRY_POINT:
        comp = EditEntryPoint;
        break;
      /*case Components.EditQueueCall:
        comp = EditQueueCall;
        break;
      case Components.EditQueueEmail:
        comp = EditQueueEmail;
        break;
      case Components.EditLibraryTTS:
        comp = EditLibraryTTS;
        break;
      case Components.EditLibraryWAV:
        comp = EditLibraryWAV;
        break;
      case Components.AddLibraryTTS:
        comp = AddLibraryTTS;
        break;
      case Components.AddLibraryWAV:
        comp = AddLibraryWAV;
        break;
      case Components.DeleteLibraryWAV:
        comp = DeleteLibraryWAV;
        break;
      case Components.DeleteLibraryTTS:
        comp = DeleteLibraryTTS;
        break;
      case Components.EditGlobal:
        comp = EditGlobal;
        break;
      case Components.EditFlash:
        break;
      case Components.EditClosingSchedule:
        comp = EditClosingSchedule;
        break;
      case Components.EditExceptionalClosure:
       comp = EditExceptionalClosure;
       break;
      case Components.EditReceiptOfEmail:
        comp = EditReceiptOfEmail;
        break;
      case Components.AddException:
        comp = AddException;
        break;
      case Components.EditException:
        comp = EditException;
        break;
      case Components.DeleteException:
        comp = DeleteException;
        break;
      case Components.EditContentEditor:
        comp = EditContentEditor;
        break;
        case Components.AddTextToSpeech:
            comp = AddTextToSpeechs;
            break;
        case Components.DeleteTextToSpeech:
            comp = DeleteTextToSpeechs;
            break;
        case Components.AddRuleEmail:
            comp = AddRuleEmail;
            break;
        case Components.editRuleEmail:
            comp = editRuleEmail;
            break;
        case Components.VISUALIZE_ROUTING_RULE:
            comp = Visualize;
            break;*/
    }


    return comp;
}

const closeModalOnBackgroundClick = () => {
    store.commit(ActionStore.CLOSE_MODAL)
}
const preventModalClose = (event: any) =>{
    event.stopPropagation();
}
</script>

<template>
    <TransitionRoot as="template" :show="isOpenModal">
        <Dialog class="relative z-10" @pointerdown="closeModalOnBackgroundClick">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                            <component :is="createComponent()" @pointerdown="preventModalClose" />
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
