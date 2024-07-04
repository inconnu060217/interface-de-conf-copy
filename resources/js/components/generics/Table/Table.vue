<script lang="ts" setup>
import {Components} from "@/constants/Components.ts";
import EntryPoint from "../../specifics/call/entryPoint/EntryPoints.vue";
import {useStore} from "vuex";
import Queues from "@/components/specifics/call/queue/Queues.vue";
//import Libraries from "../../specifics/call/library/Libraries.vue";
/*import QueuesEmail from "../../specifics/email/queue/Queues.vue";
import ContentEditors from "../../specifics/email/content_editor/ContentEditors.vue";
import RoutingRules from "../../specifics/email/routing_rule/RoutingRules.vue";
import AcknowledgementsOfReceip from "../../specifics/email/acknowledgements_of_receip/AcknowledgementsOdReceip.vue"
import Assignment from "../../specifics/call/assignment/Assignment.vue";
import textToSpeechs from "../../specifics/call/text_to_speech/TextToSpeechs.vue";
import Traces from "../../specifics/advanced/traces/Traces.vue";*/

const store = useStore();
const create = () => {
    let comp: any;
    let componentName = store.getters.componentTable;
    switch (componentName) {
        case Components.EntryPoint:
            comp = EntryPoint;
            break;
        case Components.QueuesCall:
            comp = Queues;
            break;
        /*case Components.Assignment:
            comp = Assignment;
            break;
        case Components.Libraries:
            comp = Libraries;
            break;
        case Components.QueuesEmail:
            comp = QueuesEmail;
            break;
        case Components.ContentEditors:
            comp = ContentEditors;
            break;
        case Components.AcknowledgementsOfReceip:
            comp = AcknowledgementsOfReceip;
            break;
        case Components.RoutingRule:
            comp = RoutingRules;
            break;
        case Components.TextToSpeech:
            comp = textToSpeechs;
            break;
        case Components.Trace:
            comp = Traces;
            break;*/
    }
    return comp;
}
</script>
<template>
  <component :is="create()"/>
</template>
