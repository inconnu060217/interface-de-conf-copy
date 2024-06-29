<script lang="ts" setup>
import { useStore} from "vuex";
import {Components} from "@/constants/Components";
import {EntryPointType} from "@/types/TEntryPoint";
import {computed, ref, Ref, watchEffect, onMounted} from "vue";
import Title from "@/components/generics/Title/Title.vue";
import {Icons} from "../../../generics/Icon/Icons";
import Icon from "../../../generics/Icon/Icon.vue"
import {ActionStore} from "@/constants/ActionStore";
import Button from "@/components/generics/button/Button.vue";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/shadcn/ui/table";



const store = useStore();
const entryPoints: Ref<EntryPointType[]> = computed(() => store.getters.findAllEntryPoint);
const searchTerm: Ref<String>= ref('');
const searchResults: Ref<EntryPointType[]> = ref([])
const heads = ["Numéro SDA","Type","Routage","Numéro présenté","Opération"]
  watchEffect(() => {
      searchEntryPoint()
  });
  function searchEntryPoint() {
      if (searchTerm.value !== '') {
          searchResults.value = entryPoints.value.filter((entryPoint: any) =>
              entryPoint.sda.includes(searchTerm.value)
          );
      } else {
          searchResults.value = entryPoints.value;
      }
  }
const editEntryPoint = (entryPointId: number) => {
  store.commit(ActionStore.OPEN_MODAL, Components.EDIT_ENTRY_POINT)
  store.commit(ActionStore.FIND_BY_ID_ENTRY_POINT_MUTATION,entryPointId)
}
</script>
<template>
    <Title title="Point d'entrée"/>
    <Table>
        <TableHeader>
            <TableRow >
                <TableHead v-for="head in heads" class="w-[100px]">
                    {{head}}
                </TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="(entryPoint, index) in searchResults" :key="index">
                <TableCell class="font-bold">{{ entryPoint.sda }}</TableCell>
                <TableCell>{{ entryPoint.nom }}</TableCell>
                <TableCell>{{ entryPoint.routage }}</TableCell>
                <TableCell >{{ entryPoint.numero_presente }}</TableCell>
                <TableCell>
                    <Button
                        text="Edit"
                        :handle-function="()=>editEntryPoint(entryPoint.id_point_entree)"
                        :alignLeft="true"
                    />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
