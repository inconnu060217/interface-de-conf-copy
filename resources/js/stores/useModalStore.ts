interface IState {
    isOpen: boolean,
    componentNameModal: undefined,
}

const initialState: IState = {
    isOpen: false,
    componentNameModal: undefined,
}

export const UseModalStore = {
    state: initialState,
    getters: {
        isOpenModal: (state: IState) => state.isOpen,
        componentNameModal: (state: IState) => state.componentNameModal
    },
    mutations: {
        openModal: (state: IState, componentName: any): void => {
            state.isOpen = true
            state.componentNameModal = componentName
        },

        closeModal: (state: IState) => {
            state.isOpen = false
            state.componentNameModal = undefined
        }
    }

}
