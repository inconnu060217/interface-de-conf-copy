export type QueueCallType = {
    id_file_attente?: number,
    kid_file_attente?: number,
    kid_planning_fa?: number,
    duree_dissuasion_tae?: number,
    duree_dissuasion_reel?: number;
    ratio_agent_attente?: number,
    id_type_debordement?: number
    id_debordement_message?: number,
    temps_post_appel?: number,
    numero_presente?: string,
    id_message?: number,
    kid_file_attente_debordement: number
    propriete?: number,
    callback_annonce_time?: number,
    callback_attente?: any,
    nom_fa_planning?: string,
    nom_file_attente?: string,
    nom_debordement?: string,
    File_attente_debordement?: string,
    nom_debordement_message?: string,
    renvoi?: string,
    created_at?: string,
    updated_at?: string

}

export type QueueCallWebServiceType = {
    serviceId?: number,
    serviceName?: string,
}
