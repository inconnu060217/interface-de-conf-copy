import {ApiEndpoint} from "../api/apiEnpoint";

const apiEndpoint = new ApiEndpoint();
export class SGroup {
    public async handleGetGroup () {
        return await apiEndpoint.handleGetGroup()
    }
}
