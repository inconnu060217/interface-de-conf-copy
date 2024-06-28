<script lang="ts" setup>
import {computed, onMounted, ref, Ref} from "vue";
import {EntryPointType, EntryPointTypeType, SviType} from "@/types/TEntryPoint";
import {useStore} from "vuex";
import { QueueCallWebServiceType} from "../../../../types/call/QueueCallType";
import {UGetIdAny} from "@/utils/UGetIdAny";
import {Constants} from "@/constants/Constants";
import {ActionStore} from "@/constants/ActionStore";
import {
    Select,
    SelectTrigger,
    SelectValue,
    SelectContent,
    SelectGroup,
    SelectItem
} from '@/components/shadcn/ui/select';
import Button from "@/components/generics/button/Button.vue";
import { Input } from '@/components/shadcn/ui/input'
import { Label } from '@/components/shadcn/ui/label'
import {SEntryPoint} from "@/services/call/SEntryPoint";

const sEntryPoint:SEntryPoint = new SEntryPoint()

    let disabled: Ref<boolean>= ref(true)
    const store = useStore()
    const entryPointCurrent = computed(()=> store.getters.findByIdEntryPoint)
    const queueCallWebServices: Ref<QueueCallWebServiceType[]> = computed(()=> store.getters.findQueueCallWebServe)
    const svis: Ref<SviType[]> = computed(()=> store.getters.findSvis)
    const entryPointTypes: Ref<EntryPointTypeType[]> = computed(()=> store.getters.findAllEntryPointType)
    let newEntryPoint: Ref<EntryPointType> = ref(Object.assign({}, entryPointCurrent.value))
    async function editEntryPoint() {
      if(newEntryPoint.value.nom === Constants.RENVOI) {
        newEntryPoint.value.routage = newEntryPoint.value.renvoi
      }
      if(newEntryPoint.value.nom === Constants.SVI) {
        newEntryPoint.value.routage = newEntryPoint.value.name_svi
        newEntryPoint.value.kid_svi = UGetIdAny.getIdSVI(newEntryPoint.value.name_svi, svis.value)
      }
      if(newEntryPoint.value.nom === Constants.FILE_ATTENTE) {
        newEntryPoint.value.routage = newEntryPoint.value.name_file_attente
        newEntryPoint.value.kid_file_attente = UGetIdAny.getIdQueueCall(newEntryPoint.value.name_file_attente, queueCallWebServices.value)
      }
      newEntryPoint.value.id_type_point_entree = UGetIdAny.getIdEntryPointType(newEntryPoint.value.nom, entryPointTypes.value)

            disabled.value = true
           setTimeout(async ()=>{
               try {
                const response: any = await sEntryPoint.handleUpdateEntryPoint(newEntryPoint.value)
                console.log(response.data)
               }catch (e){
                   console.error(e)
               }finally {
                   disabled.value = false
               }
            },2000)


      /*store.dispatch(ActionStore.UPDATE_ENTRY_POINT_ACTION, newEntryPoint.value).then(async (response)=>{
        disabled.value = true
        if(response.ok && response.status === 200) {
          const jsonResponse = await response.json()
          store.commit(ActionStore.OPEN_ALERT, jsonResponse.message )
          disabled.value = false
          store.commit(ActionStore.CLOSE_MODAL)
          setTimeout(()=> {
            store.commit(ActionStore.CLOSE_ALERT)
          },3000)
        }
      })*/
    }

</script>
<template>
    <div class="grid gap-4 py-4">
        <div class="grid grid-cols-4 items-center gap-4">
            <Label for="name" class="text-right text-secondary font-bold">
                Type
            </Label>
            <Select v-model="newEntryPoint.nom" >
                <SelectTrigger class="w-[250px]">
                    <SelectValue>
                        {{ newEntryPoint.nom }}
                    </SelectValue>
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <template v-for="entryPointType in entryPointTypes" :key="entryPointType.id_type_point_entree">
                            <SelectItem :value="entryPointType.nom">
                                {{ entryPointType.nom }}
                            </SelectItem>
                        </template>
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>
        <div class="grid grid-cols-4 items-center gap-4" >
            <Label for="username" class="text-right text-secondary font-bold">
                Routage
            </Label>
            <Input
                v-if="newEntryPoint.nom === Constants.RENVOI"
                v-model="newEntryPoint.renvoi"
                class="col-span-3 text-secondary bg-third"
            />
            <Select v-if="newEntryPoint.nom === Constants.SVI" v-model="newEntryPoint.name_svi" >
                <SelectTrigger class="w-[250px]">
                    <SelectValue>
                        {{ newEntryPoint.name_svi }}
                    </SelectValue>
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <template v-for="svi in svis" :key="svi.id">
                            <SelectItem :value="svi.name">
                                {{ svi.name }}
                            </SelectItem>
                        </template>
                    </SelectGroup>
                </SelectContent>
            </Select>
            <Select v-if="newEntryPoint.nom === Constants.QUEUE_CALL" v-model="newEntryPoint.name_file_attente" >
                <SelectTrigger class="w-[250px]">
                    <SelectValue>
                        {{ newEntryPoint.name_file_attente }}
                    </SelectValue>
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <template v-for="queueCallWebService in queueCallWebServices" :key="queueCallWebService.serviceId">
                            <SelectItem :value="queueCallWebService.serviceName">
                                {{ queueCallWebService.serviceName }}
                            </SelectItem>
                        </template>
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>
        <div class="grid grid-cols-4 items-center gap-4">
            <Label for="name" class="text-right text-secondary font-bold">
                Numéro présenté
            </Label>
            <Input
                v-model="newEntryPoint.numero_presente"
                class="col-span-3 text-secondary bg-third"
            />
        </div>
        <Button
            text="Valider"
            :handle-function="()=>editEntryPoint()"
            :isDisabled="disabled"
        />
    </div>
</template>
