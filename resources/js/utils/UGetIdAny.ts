import {EntryPointTypeType, SviType} from "../types/TEntryPoint";
import {QueueCallWebServiceType} from "../types/TQueueCall";

export class UGetIdAny {
    static getIdQueueCall = (name: string, dataQueueCalls: QueueCallWebServiceType[]) => {
        return Number(String(dataQueueCalls.find((QueueCall: any) => QueueCall.serviceName === name)?.serviceId))
    }

    static getIdSVI = (name: string, dataSvis: SviType[]) => {
        console.log(name)
        console.log(dataSvis)
        return Number(String(dataSvis.find((svi: any) => svi.name === name)?.id))
    }

    static getIdEntryPointType = (nameEntryPointType: string, data: EntryPointTypeType[]) => {
        return data.find((type: any)=>type.nom === nameEntryPointType)?.id_type_point_entree
    }
}
