import {ApiEndpoint} from "../api/apiEnpoint";

const apiEndpoint = new ApiEndpoint();
export class MenuService {
    public async handleGetMainMenu () {
        return await apiEndpoint.handleGetMainMenu()
    }
}
