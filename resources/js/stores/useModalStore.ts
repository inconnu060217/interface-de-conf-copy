import {b} from "vite/dist/node/types.d-aGj9QkWt";

interface IState {
    isOpen: boolean,
    isAlert: boolean,
    textAlert: string,
    componentNameModal: undefined,
}

const initialState: IState = {
    isOpen: false,
    isAlert: false,
    textAlert: "",
    componentNameModal: undefined,
}

export const UseModalStore = {
    state: initialState,
    getters: {
        isOpenModal: (state: IState) => state.isOpen,
        componentNameModal: (state: IState) => state.componentNameModal,
        isOpenAlert: (state: IState) => state.isAlert,
        textAlert:(state: IState) => state.textAlert,
    },
    mutations: {
        openModal: (state: IState, componentName: any): void => {
            state.isOpen = true
            state.componentNameModal = componentName
        },

        closeModal: (state: IState) => {
            state.isOpen = false
            state.componentNameModal = undefined
        },
        openAlert: (state: IState, text: string): void => {
            state.isAlert = true
            state.textAlert = text
        },

        closeAlert: (state: IState) => {
            state.isAlert = false
        }

    }

}
