import {EntryPointType, EntryPointTypeType, SviType} from "../types/TEntryPoint";
import {QueueCallType} from "../types/TQueueCall";

export class USort {

    static entryPointSort(data: EntryPointType[]) {
        data.sort((a: any, b: any) => {
            const sdaA = a.sda;
            const sdaB = b.sda;

            if (sdaA < sdaB) {
                return -1; // a vient avant b
            }
            if (sdaA > sdaB) {
                return 1; // b vient avant a
            }
            return 0; // a et b sont égaux
        });
        return data;
    }
    static queueCallSort(data: QueueCallType[]) {
        data.sort((a: any, b: any) => {
            const fileAttenteA = a.nom_file_attente;
            const fileAttenteB = b.nom_file_attente;

            if (fileAttenteA < fileAttenteB) {
                return -1; // a vient avant b
            }
            if (fileAttenteA > fileAttenteB) {
                return 1; // b vient avant a
            }
            return 0; // a et b sont égaux
        });
        return data;
    }
}
