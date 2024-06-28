<script lang="ts">

import {ref, Ref} from "vue";
import {useStore} from "vuex";
import {LibraryEnum} from "../../../../enums/Library";
import {Icons} from "../../../generics/Icon/Icons";
import Icon from "../../../generics/Icon/Icon.vue"
import {UCookies} from "../../../../utils/UCookies";


export default {
    name: "ConvertingFile",
    components: {Icon},
    computed: {
        Icons() {
            return Icons
        }
    },
    props: {
        fileWav: {
            type: Object,
            required: true,
        },
        name: {
            type: String,
            required: true,
        }
    },
    setup(props) {
        const store = useStore()
        let isError: Ref<boolean> = ref(false)
        let error: Ref<string> = ref("")
        let sourceDestination: Ref<string> = ref("")
        let disabled: Ref<boolean>= ref(false)

        function handleConverting(event: any) {
            event.preventDefault();
            if (!sourceDestination.value) {
                isError.value = true
                error.value = "Renseignez le nom du fichier converti au bon format."
            } else {
                const sourceDestinationConv = `conv_${sourceDestination.value}.wav`
                let formData = new FormData();
                formData.append(LibraryEnum.nom_personnalise, props.name)
                formData.append(LibraryEnum.path_banque_message, props.fileWav)
                formData.append("source_destination", sourceDestinationConv)
                formData.append(LibraryEnum.id_type_diffusion, '1')
                formData.append(LibraryEnum.id_langue, null)
                formData.append(LibraryEnum.type, LibraryEnum.WAV)
                formData.append(LibraryEnum.directoryMedia, UCookies.getCookie("media_directory"))
                store.dispatch("convertingFileFileLibraryAction", formData).then((response) => {
                    disabled.value = true
                    if(response === 500) {
                        isError.value = true
                        error.value = "Une erreur s'est produite lors de la conversion."
                        setTimeout(()=> {
                            store.commit(LibraryEnum.closeModal)
                        },3000)
                    }
                    if(response.ok && response.status === 200) {
                        store.commit(LibraryEnum.openAlert, LibraryEnum.messageAdd )
                        store.commit(LibraryEnum.closeModal)
                        setTimeout(()=> {
                            store.commit(LibraryEnum.closeAlert)
                        },3000)
                    }
                })

            }


        }

        return {
            error,
            isError,
            sourceDestination,
            disabled,
            fileWavName: props.fileWav.name,
            name: props.name,
            handleConverting
        }

    }
}
</script>
<template>
    <form class="form" @submit="handleConverting"  enctype="multipart/form-data" method="post">

        <div class="form-content-field-edit">
            <div class="form-label">
                <label>Nom :</label>
            </div>
            <p>{{ name}}</p>
        </div>

        <div class="form-content-field-edit">
            <div class="form-label">
                <label>Fichier a converti :</label>
            </div>
            <p>{{ fileWavName}}</p>
        </div>

        <div class="form-content-field">
            <div class="form-label">
                <label>Nom du fichier converti au bon format :</label>
            </div>
            <div class="flex">
                <p class="py-[0.375rem]">conv_</p>
                <input
                    v-model="sourceDestination"
                    :class="{' border-2 border-red-600':  isError && !sourceDestination}"
                    class="form-input" type="text"
                />
                <p class="py-[0.375rem]">.wav</p>
            </div>

        </div>

        <div class="form-content-field">
            <p class=" text-center text-red-600 mb-1" v-if="isError"
            > <Icon  :name="Icons.IconOutlineError" cursor-pointer /> {{ error }}</p>
        </div>

        <div class="form-button-container">
            <button class="form-button-converting form-button" :disabled="disabled" type="submit" >Convertir et Créer</button>
        </div>
    </form>
</template>
<style scoped>

</style>
