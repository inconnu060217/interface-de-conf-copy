import axiosInstance from './axiosInstance';
import {EntryPointType} from "../types/TEntryPoint";

export class ApiEndpoint {
    public static defaultHeaders(): { Accept: string; "Content-Type": string } {
        return {
            'Accept': 'application/json, text/plain, */*',
            'Content-Type': 'application/json',
        }
    }

    public static defaultHeadersFile(): { Accept: string; "Content-Type": string } {
        return {
            'Accept': 'application/json',
            'Content-Type': 'multipart/form-data',
        }
    }
    public async handleGetMainMenu() {
        return await axiosInstance.get(`mainMenu`);
    }
    public async handleGetSecondaryMenu(media: string) {
        return await axiosInstance.get(`secondaryMenu?media=${media}`);
    }
    public async handleGetGroup() {
        return await axiosInstance.get(`group`);
    }
    public async handleGetEntryPoint (idGroupe: string, kidGroupe: string) {
        return await axiosInstance.get(`entryPoint?idGroupe=${idGroupe}&kidGroupe=${kidGroupe}`);
    }
    public async handleGetEntryPointType () {
        return await axiosInstance.get(`entryPointType`);
    }
    public async handleGetSvis (kidGroupe: string) {
        return await axiosInstance.get(`svis?kidGroupe=${kidGroupe}`);
    }
    public async handleGetQueueCallWebService (kidGroupe: string) {
        return await axiosInstance.get(`queueCallWebService?kidGroupe=${kidGroupe}`);
    }
    public async handleGetQueueCall (idGroupe: string, kidGroupe: string) {
        return await axiosInstance.get(`queueCall?idGroupe=${idGroupe}&kidGroupe=${kidGroupe}`);
    }
    public async handleUpdateEntryPoint (data: EntryPointType) {
        return await axiosInstance.put(`entryPoint?entryPointId=${data.id_point_entree}`,
            {data},
            { headers: ApiEndpoint.defaultHeaders() });
    }
}



