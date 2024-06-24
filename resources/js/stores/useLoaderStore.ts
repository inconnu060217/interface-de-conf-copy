import { reactive } from 'vue';

interface IState {
    isLoading: boolean;
}

const initialState: IState = {
    isLoading: false
};

export const loaderStore = reactive({
    state: { ...initialState },
    mutations: {
        openSpinner() {
            loaderStore.state.isLoading = true;
        },
        closeSpinner() {
            loaderStore.state.isLoading = false;
        },
    },
});
