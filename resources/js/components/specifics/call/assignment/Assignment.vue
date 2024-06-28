<script lang="ts">
import { useStore} from "vuex";
import {Assignment} from "../../../../enums/Assignment";
import ClosingSchedules from "./closing_schedule/ClosingSchedules.vue"
import Global from "./global/Global.vue";
import ExceptionalClosure from "./exceptional_closure/ExceptionalClosure.vue";
import Accordion from "../../../generics/Accordion/Accordion.vue";
import {Icons} from "../../../generics/Icon/Icons";
import Icon from "../../../generics/Icon/Icon.vue"
import {computed, onMounted, ref, Ref} from "vue";
import Title from "../../../generics/Title/Title.vue";
export default {
    name: Assignment.name,
    components: {
      Icon,
      Accordion,
      ClosingSchedules,
      Global,
      ExceptionalClosure,
      Title
    },
    setup() {
        const store = useStore();

        const assignment = Assignment

        let activeSection: Ref<undefined> = ref(null)

        function toggleActiveSection(section: any) {
            if (activeSection.value === section) {
                activeSection.value = null;
            } else {
                activeSection.value = section;
            }
        }

        const categoryAssignments = computed(() => store.getters.findAllCategoryAssignment)

        onMounted(() => {
            store.dispatch(Assignment.findAllCategoryAssignmentAction)
        })

        return {
          categoryAssignments,
          toggleActiveSection,
          activeSection,
          assignment
        }
    },
    computed: {
        Icons() {
            return Icons
        }
    }
}
</script>
<template>
  <Title :title="assignment.title"/>
    <Accordion
            v-for="(categoryAssignment) in categoryAssignments"
            :header="categoryAssignment.nom"
            :isExpanded="activeSection === categoryAssignment.id_categorie_message"
            @toggle="toggleActiveSection(categoryAssignment.id_categorie_message)"
    >
      <Global v-if="categoryAssignment.nom !== assignment.planning && categoryAssignment.nom !== assignment.exceptionnelle"  :idCategoryAssignment="categoryAssignment.id_categorie_message" />
      <ClosingSchedules v-if="categoryAssignment.nom === assignment.planning" :idCategoryAssignment="categoryAssignment.id_categorie_message" />
    <ExceptionalClosure v-if="categoryAssignment.nom === assignment.exceptionnelle" :idCategoryAssignment="categoryAssignment.id_categorie_message" />

    </Accordion>
</template>
<style scoped>

</style>
