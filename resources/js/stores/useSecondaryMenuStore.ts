import {TSecondaryMenu} from "../types/TSecondaryMenu";
import {SMenu} from "../services/SMenu";

const serviceMenu: SMenu = new SMenu()

interface IState {
    secondaryMenus: Array<TSecondaryMenu>,
}

const initialState: IState = {
    secondaryMenus: undefined,
}
export const secondaryMenuStore = {
    state: initialState,
    getters: {
        handleFindAllASecondary(state: any) {
            return state.secondaryMenus;
        },
    },
    mutations: {
        handleFindAllMutation(state: any, data: TSecondaryMenu) {
            state.secondaryMenus = data;
        },
    },
    actions: {
        handleFindAllASecondary(context: any,  media: string) {
            serviceMenu.handleGetSecondaryMenu(media)
                .then(async (response: any) => {
                    if (!response.ok) {
                        return;
                    }
                    const jsonResponse = await response.json();
                    context.commit("handleFindAllMutation", jsonResponse)
                })
                .catch((err) => {
                    console.error(err.message);
                });
        },
    }
}
