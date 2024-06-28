<script lang="ts">
import {computed, onMounted, ref, Ref, watchEffect} from "vue";
import {useStore} from "vuex";
import {Icons} from "../../../../generics/Icon/Icons";
import Icon from "../../../../generics/Icon/Icon.vue";
import {LibraryEnum} from "../../../../../enums/Library";
import {Assignment} from "../../../../../enums/Assignment";
import {Components} from "../../../../../vars/Components";
import {UAssignment} from "../../../../../utils/UAssignment";
import {PlanningType} from "../../../../../types/call/planningType";

export default  {
  name: Assignment.ClosingSchedules,
  props: {
    idCategoryAssignment: {
      type: Number,
      required: true
    }
  },
  components: {
    Icon
  },
  computed: {
    Icons() {
      return Icons
    }
  },
  setup(props){
    let heads = [Assignment.th1, Assignment.th3planning, ""]
    const store = useStore()
    let assignments = computed(()=> store.getters.findAllAssignmentPlanning)
    let diffusionTypes = computed(()=> store.getters.findAllDiffusionType)
    const searchTerm: Ref<String>= ref('');
    const searchResults: Ref<PlanningType[]> = ref([])

    function searchAssignment() {
      if (searchTerm.value !== '') {
        searchResults.value = assignments.value.filter((assignment: any) =>
            assignment.nom_planning.includes(searchTerm.value)
        );
      } else {
        searchResults.value = assignments.value;
      }
    }

    function editClosingSchedules(id: any) {
      store.commit(Assignment.findByIdAssignmentMutation, {category: Assignment.SCHEDULE, id})
        store.commit(Assignment.openModal, Components.EditClosingSchedule)
    }

    watchEffect(() => {
      searchAssignment()
    });

    onMounted(() => {
      store.dispatch(Assignment.findAllPlanningAction, props.idCategoryAssignment)
      store.dispatch(Assignment.findAllDiffusionTypeAction)
    })
    return{
      heads,
      assignments,
      LibraryEnum,
      searchTerm,
      searchResults,
      diffusionTypes,
      editClosingSchedules,
      UAssignment,
      Assignment,
      searchAssignment,
    }
  },
}


</script>
<template>
  <div class="search">
    <Icon :name="Icons.IconSharpSearch" cursor-pointer class="icon"/>
    <input type="text" name="entrypoint" id="entrypoint"
           placeholder="Rechercher par nom"
           v-model="searchTerm" @input="searchAssignment"
    />
  </div>
  <table>
    <thead>
    <th v-for="(head, index) in heads"  :key="index" scope="col">
      {{ head }}
    </th>
    </thead>
    <tbody>
    <tr v-for="(assignment) in searchResults" :key="assignment.id_planning">
      <td>{{assignment.nom_planning}}</td>
      <td>{{assignment.nom}}</td>
      <td @click="editClosingSchedules(assignment.id_planning)">
        <Icon :name="Icons.IconEdit" cursor-pointer class="icon"/>
      </td>
    </tr>
    </tbody>
  </table>
</template>
<style scoped>
.col-message {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.message-active {
  display: flex;
  align-items: center;
}
</style>
