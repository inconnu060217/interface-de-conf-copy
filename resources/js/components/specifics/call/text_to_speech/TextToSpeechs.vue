<script lang="ts">
import {useStore} from "vuex";
import {Components} from "../../../../vars/Components";
import Title from "../../../generics/Title/Title.vue";
import {Icons} from "../../../generics/Icon/Icons";
import Icon from "../../../generics/Icon/Icon.vue";
import {computed, onMounted, ref, Ref} from "vue";
import {TextToSpeechType} from "../../../../types/call/TextToSpeechType";
import {UHttp} from "../../../../utils/UHttp";
import {GlobalVars} from "../../../../vars/Urls";
import axios from "axios";
export default {
    name: "TextToSpeech",
    components: {
        Title,
        Icon,
    },
    setup() {
        const store = useStore()
        const heads: string[] = ["Nom du fichier", "Voix", "Genre", "Langue", "Débit vocal", "Lecture", "", ""]
        const textToSpeechs: Ref<TextToSpeechType[]> = computed(() => store.getters.findTextToSpeech)
        let isPlaying: Ref<boolean> = ref(false)
        let indexPlaying: Ref<number> = ref(undefined)
        let audioElements: Ref<HTMLAudioElement[]> = ref([])

       function add() {
           store.commit("openModal", "AddTextToSpeech")
       }
        function deletedTextToSpeech(id) {
            store.commit("findByIdTextToSpeechMutation", id)
            store.commit("openModal", "DeleteTextToSpeech")
        }

        async function togglePlayback(file: string, index: number) {
            let audioElement = audioElements.value[index];

            const response = await UHttp.get(`${GlobalVars.BASE_URL_API}texttospeech/get/audio/${file}`, { responseType: 'blob' });

            if (response && response.ok) {
                const audioBlob = await response.blob();
                const audioURL = URL.createObjectURL(audioBlob);

                // Si l'élément audio n'existe pas, créez-en un nouveau
                if (!audioElement) {
                    audioElement = new Audio(audioURL);
                    audioElements.value[index] = audioElement;

                    // Écouteur d'événement pour gérer la fin de la lecture
                    audioElement.addEventListener('ended', () => {
                        if (index === indexPlaying.value) {
                            indexPlaying.value = null;
                            audioElements.value[index] = null;
                        }
                    });
                }
                // Vérification et pause de l'élément précédent s'il est en cours de lecture
                if (indexPlaying.value !== undefined && indexPlaying.value !== index) {
                    const currentIndex = indexPlaying.value;
                    const previousAudio = audioElements.value[currentIndex];
                    if (previousAudio && !previousAudio.paused) {
                        previousAudio.pause();
                    }
                }

                if (indexPlaying.value === index) {
                    if (!audioElement.paused) {
                        audioElement.pause();
                        indexPlaying.value = index;
                    }
                    else {
                        await audioElement.play();
                        indexPlaying.value = index
                    }
                } else {
                    // Démarrer la lecture du nouvel élément
                    await audioElement.play();
                    indexPlaying.value = index;
                }
            }
        }

        function getDownloadLink(filename: string) {

            axios({
                url: `${GlobalVars.BASE_URL_API}texttospeech/get/download/${filename}`,
                method: "GET",
                responseType: 'blob'
            }).then((response ) => {

                // Crée un objet URL pour le blob de données
                let fileURL = window.URL.createObjectURL(new Blob([response.data]));

                let fileLink = document.createElement('a');

                // Crée un élément <a> pour le lien de téléchargement
                fileLink.href = fileURL;

                // Spécifie le nom de fichier lors du téléchargement
                fileLink.download = filename;

                // Ajoute l'élément <a> à la page
                document.body.appendChild(fileLink);

                // Déclenche le téléchargement
                fileLink.click();

                // Libère l'URL de l'objet blob
                window.URL.revokeObjectURL(fileURL);

                // Supprime l'élément <a> de la page
                document.body.removeChild(fileLink);
                })
                .catch(error => {
                    console.error(error);
                });

        }

        onMounted(() => {
            store.dispatch("findTextToSpeechAction")
        })
       return {
           heads,
           add,
           deletedTextToSpeech,
           togglePlayback,
           getDownloadLink,
           textToSpeechs,
           isPlaying,
           indexPlaying,
           audioElements,
       }
    },
    data() {
        return {}
    },
    computed: {
        Icons() {
            return Icons
        }
    }
}
</script>

<template>
    <Title title="Synthèse vocale" />
    <div>
        <div class="flex justify-end mb-1 content-add">
            <div
                @click="add()"
                class="bg-primary flex items-center px-[10px] py-[1px] rounded-sm cursor-pointer text-base">
                <Icon :name="Icons.IconOutlinePlus"/>
            </div>
        </div>
    </div>
    <table>
        <thead>
        <th v-for="(head, index) in heads"  :key="index" scope="col">
            {{ head }}
        </th>
        </thead>
        <tbody>
            <tr v-for="(textToSpeech, index) in textToSpeechs" :key="textToSpeech.id">
                <td>{{textToSpeech.name_file}}</td>
                <td>{{textToSpeech.voice}}</td>
                <td>{{textToSpeech.gender}}</td>
                <td>{{textToSpeech.language}}</td>
                <td>{{textToSpeech.speaking_rate}}</td>
                <td><Icon @click="togglePlayback(textToSpeech.name_file, index)" :name="indexPlaying === index ? Icons.IconBaselinePauseCircle : Icons.IconBaselinePlayCircle" cursor-pointer class="icon"/></td>
                <td>
                    <Icon @click="getDownloadLink(textToSpeech.name_file)" :name="Icons.IconDownload" cursor-pointer class="icon icon2x"/>
                </td>
                <td><Icon  @click="deletedTextToSpeech(textToSpeech.id)" :name="Icons.IconBaselineDelete" cursor-pointer class="icon"/></td>
            </tr>
        </tbody>
    </table>

</template>
