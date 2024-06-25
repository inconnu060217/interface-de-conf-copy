import {ApiEndpoint} from "../api/apiEnpoint";

const apiEndpoint = new ApiEndpoint();
export class SMenu {
    public async handleGetMainMenu () {
        return await apiEndpoint.handleGetMainMenu()
    }

    public async handleGetSecondaryMenu (media: string) {
        return await apiEndpoint.handleGetSecondaryMenu(media)
    }
}
