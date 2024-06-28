interface IState {
    componentName: string,
}

const initialState: IState = {
    componentName: undefined,
}

export const tableStore = {
    state: initialState,
    getters: {
        componentTable: (state: IState) => state.componentName
    },
    mutations: {
        openTable: (state: IState, componentName: any): void => {
            state.componentName = componentName
        },
    }

}
