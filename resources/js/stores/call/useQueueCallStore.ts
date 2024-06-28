import {QueueCallType, QueueCallWebServiceType} from "../../types/TQueueCall";
import {SQueueCall} from "../../services/call/SQueueCall";
import { USort } from "../../utils/USort";
import {UCookies} from "../../utils/UCookies";

const sQueueCall: SQueueCall = new SQueueCall();

export type IState = {
    queueCalls: QueueCallType[],
    currentQueueCall: QueueCallType | null
    isLoadingQueueCalls: boolean,
    queueCallWebServices: QueueCallWebServiceType[],
    isLoadingQueueCallWebServices: boolean,
}

const initialState: IState = {
    queueCalls: [],
    isLoadingQueueCalls: false,
    currentQueueCall: {
        id_file_attente: 0,
        kid_file_attente: 0,
        kid_planning_fa: 0,
        duree_dissuasion_tae: 0,
        duree_dissuasion_reel: 0,
        ratio_agent_attente: 0,
        id_type_debordement: 0,
        kid_file_attente_debordement: 0,
        propriete: 0,
        id_message: 0,
        id_debordement_message: 0,
        temps_post_appel: 0,
        callback_annonce_time: 0,
        callback_attente: undefined,
        numero_presente: "",
        nom_debordement_message: "",
        nom_fa_planning: "",
        nom_file_attente: "",
        nom_debordement: "",
        File_attente_debordement: "",
        renvoi: "",
        created_at: "",
        updated_at: ""
    },
    queueCallWebServices: [],
    isLoadingQueueCallWebServices: false,
}
export const QueueCallStore = {
    state: initialState,
    getters: {
        findQueueCall(state: any) {
            return state.queueCalls;
        },
        findQueueCallWebServe(state: any) {
            return state.queueCallWebServices;
        },
        findByIdQueueCall: (state: IState)=> {
            return state.currentQueueCall
        }
    },
    mutations: {
        findAllQueueCallMutation(state: any, data: QueueCallType):void {
            state.queueCalls = data;
            state.isLoadingQueueCalls = true
        },
        findAllQueueCallWebServiceMutation(state: any, data: QueueCallWebServiceType[]):void {
            state.queueCallWebServices = data;
            state.isLoadingQueueCallWebServices = true
        },
        findByIdQueueCallMutation(state: any, queueCallId: number):void {
            state.currentQueueCall = state.queueCalls.find((queueCall: any) => queueCall.id_file_attente === queueCallId)
        },
        updateQueueCallMutation(state: any, data: QueueCallType): void {
            state.queueCalls = state.queueCalls.map((queueCall: any) => {
                if (queueCall.id_file_attente === data.id_file_attente) {
                    return data
                } else {
                    return queueCall
                }
            })
        }
    },
    actions: {
        /*async findAllQueueCallAction({ state, commit }) {
            if(!state.isLoadingQueueCalls) {
                let id_groupe: number| any = UCookies.getCookieIdGroupe()
                let kid_groupe: number| any = UCookies.getCookieKidGroupe()
                queueCallService.get(id_groupe, kid_groupe).then(async (response):Promise<Response | undefined>=> {
                    if (!response.ok) {
                        return;
                    }
                    const jsonResponse = await response.json();
                    commit("findAllQueueCallMutation", USort.queueCallSort(jsonResponse));
                }).catch((error) => {
                    console.log(error);
                });
            }
            return state.queueCalls
        },*/
        async findAllQueueCallWebserviceAction({state, commit}){
            let kiGroupe: number|any = UCookies.getCookieKidGroupe()
            if(!state.isLoadingQueueCallWebServices) {
                sQueueCall.handleGetQueueCallWebService(kiGroupe).then(async (response: any):Promise<Response| undefined>=> {
                    if (response.status !== 200) {
                        return;
                    }
                    commit("findAllQueueCallWebServiceMutation", response.data);
                }).catch((error) => {
                    console.log(error);
                });
            }
            return state.queueCallWebServices
        },
/*        async updateQueueCallAction({ commit }, data: QueueCallType):Promise<Response| undefined> {
            try {
                let dataSend = {
                    id_file_attente: data.id_file_attente,
                    kid_planning_fa: data.kid_planning_fa,
                    duree_dissuasion_tae: data.duree_dissuasion_tae,
                    duree_dissuasion_reel: data.duree_dissuasion_reel,
                    ratio_agent_attente: data.ratio_agent_attente,
                    id_type_debordement: data.id_type_debordement,
                    renvoi: data.renvoi,
                    kid_file_attente_debordement: data.kid_file_attente_debordement,
                    callback_attente: data.callback_attente,
                    id_debordement_message : data.id_debordement_message,
                    priorite: data.propriete,
                    numero_presente: data.numero_presente,
                    temps_post_appel: data.temps_post_appel,
                    temps_proposition_callabck : data.callback_annonce_time
                }
                const response = await queueCallService.update(dataSend);
                if (!response.ok) {
                    return;
                }
                commit("updateQueueCallMutation", data);
                return response;
            } catch (error) {
                console.error(error);
            }
        },*/
    }
}
