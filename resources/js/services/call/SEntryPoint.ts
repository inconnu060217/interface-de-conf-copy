import {ApiEndpoint} from "../../api/apiEnpoint";
import {EntryPointType} from "../../types/TEntryPoint";

const apiEndpoint = new ApiEndpoint();

export class SEntryPoint {
    public async handleGetEntryPoint (idGroupe: string, kidGroupe: string) {
        return await apiEndpoint.handleGetEntryPoint(idGroupe, kidGroupe)
    }
    public async handleGetEntryPointType () {
        return await apiEndpoint.handleGetEntryPointType()
    }
    public async handleGetSvis (kidGroupe: string) {
        return await apiEndpoint.handleGetSvis(kidGroupe)
    }
    public async handleUpdateEntryPoint (data: EntryPointType) {
        return await apiEndpoint.handleUpdateEntryPoint(data)
    }
}
