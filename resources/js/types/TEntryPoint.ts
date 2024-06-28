export type EntryPointType = {
    id_point_entree?: number,
    id_groupe?: number,
    numero_presente?: string,
    sda?: string,
    id_type_point_entree?: number,
    kid_svi?: number,
    kid_file_attente?: number,
    renvoi?: string,
    created_at?: string,
    updated_at?: string,
    nom?: string,
    routage?: string,
    name_svi?: "",
    name_file_attente?: ""
}

export type EntryPointTypeType = {
    id_type_point_entree?: number,
    nom?: string,
}

export type SviType = {
    id?: number,
    name?: string,
}
