<script lang="ts">
import {Icons} from "../../../generics/Icon/Icons";
import Icon from "../../../generics/Icon/Icon.vue";
import { useStore} from "vuex";
import {computed, onMounted, Ref} from "vue";
import {Assignment} from "../../../../enums/Assignment.js";
import {CategoryAssignmentType} from "../../../../types/call/CategoryAssignmentType.js";

export default {
    name: "LibraryUsage",
    components: {
        Icon,
    },
    props: {
        isExpanded: {
            type: Boolean,
            default: false
        },
        nomsUtilisation: {
            type: Array,
            default: false
        }
    },
    setup(props) {
        const store = useStore();
        const categoryAssignments: Ref<CategoryAssignmentType[]> = computed(() => store.getters.findAllCategoryAssignment)

        onMounted(() => {
            store.dispatch(Assignment.findAllCategoryAssignmentAction)
        })

        return {
            categoryAssignments,
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
    <div v-show="isExpanded" @click="$emit('close-infos')" class="main-container-usage">
        <div class="container-usage">
          <div class="icon-usage">
            <Icon :name="Icons.IconClose" class-name="cursor-pointer" :on-click="() => $emit('close-infos')" class="icon"/>
          </div>
            <h2>AFFECTATION</h2>
            <table>
                <thead>
                <tr>
                    <th v-for="(categoryAssignment) in categoryAssignments" :key="categoryAssignment.id_categorie_message" scope="col">{{ categoryAssignment.nom }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(nomUtilisation, index) in nomsUtilisation" :key="index">
                    <td v-for="categoryAssignment in categoryAssignments" :key="categoryAssignment.id_categorie_message">
                        <span v-if="categoryAssignment.id_categorie_message === nomUtilisation.id_categorie_message">
                            {{ nomUtilisation.nom }}
                          </span>
                        <span v-else>-</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
  .main-container-usage {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, .5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 600;
  }
  .container-usage {
    padding: 20px;
    border-radius: 15px;
    background-color: var(--background);
    box-shadow: rgba(149, 157, 165, 0.2) 0 8px 24px;
    height: max-content;
  }
  h1 {
    font-weight: bold;
    text-transform: uppercase;
    font-size: 1.125rem;
    line-height: 1.75rem;
  }
  h2 {
    line-height: 1.75rem;
    font-weight: 600;
    font-size: 1rem;
    text-align: center;
  }
  .icon-usage {
    width: 100%;
    display: flex;
    justify-content: flex-end;
  }
  .content-usage {
    display: flex;
  }
</style>
