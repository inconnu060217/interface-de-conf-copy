import {ApiEndpoint} from "../../api/apiEnpoint";

const apiEndpoint = new ApiEndpoint();

export class SQueueCall {
    public async handleGetQueueCallWebService(kidGroupe: string) {
        return await apiEndpoint.handleGetQueueCallWebService(kidGroupe)
    }
}
