import {EntryPointType, EntryPointTypeType, SviType} from "../../types/TEntryPoint";
import {SEntryPoint} from "../../services/call/SEntryPoint";
import {USort} from "../../utils/USort";
import {UCookies} from "../../utils/UCookies";
import {a} from "vite/dist/node/types.d-aGj9QkWt";

const sEntryPoint: SEntryPoint = new SEntryPoint();

export type IState = {
    entryPoints: EntryPointType[],
    entryPointTypes: EntryPointTypeType[],
    currentEntryPoint: EntryPointType | null,
    svis: SviType[],
    isLoadingSvis: boolean,
    isLoadingEntryPoint: boolean,
    isLoadingEntryPointType: boolean,
}

const initialState: IState = {
    entryPoints: [],
    entryPointTypes: [],
    svis: [],
    isLoadingSvis: false,
    isLoadingEntryPoint: false,
    isLoadingEntryPointType: false,
    currentEntryPoint: {
        id_point_entree: 0,
        id_groupe: 0,
        sda: "",
        id_type_point_entree: 0,
        kid_svi:0,
        kid_file_attente: 0,
        numero_presente: "",
        renvoi: "",
        name_svi: "",
        name_file_attente: ""
    },

}

export const entryPointStore = {
    state: initialState,
    getters: {
        findAllEntryPoint: (state: IState) => state.entryPoints,
        findAllEntryPointType: (state: IState) => state.entryPointTypes,
        findByIdEntryPoint: (state: IState)=> state.currentEntryPoint,
        findSvis: (state: IState)=> state.svis,
    },
    mutations: {
        findAllEntryPointMutation(state: any, data: EntryPointType[]): void {
            state.entryPoints = data;
            state.isLoadingEntryPoint = true;
        },
        findAllEntryPointTypeMutation(state: any, data: EntryPointTypeType[]): void {
            state.entryPointTypes = data;
            state.isLoadingEntryPointType = true;
        },
        findAllSviMutation(state: any, data: SviType[]):void {
            state.svis = data;
            state.isLoadingSvis = true
        },

        findByIdEntryPointMutation(state: any, entryPointId: number): void {
            state.currentEntryPoint = state.entryPoints.find((entryPoint: any) => entryPoint.id_point_entree === entryPointId)
        },
        updateEntryPointMutation(state: any , data: EntryPointType): void {
            state.entryPoints = state.entryPoints.map((entryPoint: any): any => {
                if (entryPoint.id_point_entree === data.id_point_entree) {
                    return data;
                } else {
                    return entryPoint;
                }
            });
        }
    },
    actions: {
         findAllEntryPointAction({ state, commit }) {
            if (!state.isLoadingEntryPoint) {
                let idGroupe: number| any = UCookies.getCookieIdGroupe()
                let kidGroupe: number| any = UCookies.getCookieKidGroupe()
                sEntryPoint.handleGetEntryPoint(idGroupe, kidGroupe).then(async (response: any):Promise<Response | any> => {
                    if (response.status !== 200) {
                        return;
                    }
                    commit("findAllEntryPointMutation", USort.entryPointSort(response.data));
                }).catch((error) => {
                    console.log(error);
                });
            }
            return state.entryPoints;
        },
        async findAllSviAction({ state, commit }) {
            if(!state.isLoadingSvis) {
                let kiGroupe: number | any = UCookies.getCookieKidGroupe()
                sEntryPoint.handleGetSvis(kiGroupe).then(async (response: any):Promise<Response| undefined>=> {
                    if (response.status !== 200) {
                        return;
                    }
                    commit("findAllSviMutation", response.data);
                }).catch((error) => {
                    console.log(error);
                });
            }
            return state.svis
        },
        findAllEntryPointTypeAction({ state, commit }) {
            if (!state.isLoadingEntryPointType) {
                sEntryPoint.handleGetEntryPointType().then(async (response: any):Promise<Response| null | undefined> => {
                    if (response.status !== 200) {
                        return;
                    }
                    commit("findAllEntryPointTypeMutation", response.data);
                }).catch((error) => {
                    console.log(error);
                });
            }
            return state.entryPointTypes;
        },
    },
};
