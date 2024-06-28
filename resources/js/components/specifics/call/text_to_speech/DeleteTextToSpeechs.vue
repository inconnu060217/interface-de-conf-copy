<script lang="ts">
import {useStore} from "vuex";
import {computed, ref, Ref, watch} from "vue";
import Icon from "../../../generics/Icon/Icon.vue";
import {Icons} from "../../../generics/Icon/Icons";
import {TextToSpeechType} from "../../../../types/call/TextToSpeechType";

export default {
    name: "DeleteTextToSpeech",
    setup() {
        const store = useStore()
        const currentTextToSpeech: Ref<TextToSpeechType> = computed(() => store.getters.findByIdTextToSpeech)

        function deletedTextToSpeech(id:number) {
            store.dispatch("deleteTextToSpeechAction", id).then(async (response) => {
                const jsonResponse = await response.json()
                if(response.ok && response.status === 200) {
                    store.commit("openAlert", jsonResponse.message)
                    store.commit("closeModal")
                    setTimeout(() => {
                        store.commit("closeAlert")
                    },3000)
                }
            })
        }
        return {
            currentTextToSpeech,
            deletedTextToSpeech
        }
    }
}
</script>
<template>
    <div class="form">
        <div class="form-content-field-delete">
            <div class="form-label">
                <label>Nom du fichier à générer :</label>
            </div>
            <p>{{currentTextToSpeech.name_file}}</p>
        </div>
        <div class="form-content-field-delete">
            <div class="form-label">
                <label>Voix :</label>
            </div>
            <p>{{currentTextToSpeech.voice}}</p>
        </div>
        <div class="form-content-field-delete">
            <div class="form-label">
                <label>Genre :</label>
            </div>
            <p>{{currentTextToSpeech.gender}}</p>
        </div>
        <div class="form-content-field-delete">
            <div class="form-label">
                <label>Langue :</label>
            </div>
            <p>{{currentTextToSpeech.language}}</p>
        </div>
        <div class="form-content-field-delete">
            <div class="form-label">
                <label>Débit du vocal :</label>
            </div>
            <p>{{currentTextToSpeech.speaking_rate}}</p>
        </div>
        <div class="form-button-container">
            <button
                class="form-button"
                type="submit"
                @click="deletedTextToSpeech(currentTextToSpeech.id)"
            >Supprimer</button>
        </div>
    </div>
</template>
