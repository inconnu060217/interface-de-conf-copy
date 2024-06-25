export class UCookies
{
    // Vérifie si un cookie contenant le nom existe, et le crée si nécessaire
    // en utilisant la première entrée de la liste des groupes.
    static checkCookie (groupsLists: Array<any>) {

        const cookieValue = this.getCookie("nom");
        if (cookieValue === null && groupsLists.length > 0) {

            const defaultCaisse = groupsLists[0];
            if(defaultCaisse !== undefined ) {
                for (const key in defaultCaisse) {
                    this.setCookie(key, defaultCaisse[key]);
                }
                return defaultCaisse.nom
            }
        } else {
            return cookieValue;
        }
    }
    // Met à jour le cookie avec les informations du groupe sélectionné
    static updateCookie (selectedGroupCurrent: string, groupsLists: Array<any>) {
        const GroupCurrent = selectedGroupCurrent;
        const group = groupsLists.find((group) => group.nom === GroupCurrent);
        for (const key of Object.keys(group)) {
            this.setCookie(key, group[key]);
        }
        location.reload()
    }
    // Crée un cookie avec la clé et la valeur spécifiées
    static setCookie(key: string, value: any){
        document.cookie = `${key}=${value};`
    }
    // Récupère la valeur du cookie avec le nom spécifié
    static getCookie(name: string) {
        const match = document.cookie.match(
            new RegExp(`(^| )${name}=([^;]+)`)
        );
        if (match) {
            return match[2];
        }
        return null;
    }
    static getCookieIdGroupe() {
        let kid_groupe = "id_groupe"
        const match = document.cookie.match(
            new RegExp(`(^| )${kid_groupe}=([^;]+)`)
        );
        if (match) {
            return Number(match[2]);
        }
        return null;
    }
    static getCookieKidGroupe() {
        let kid_groupe = "kid_groupe"
        const match = document.cookie.match(
            new RegExp(`(^| )${kid_groupe}=([^;]+)`)
        );
        if (match) {
            return Number(match[2]);
        }
        return null;
    }
    // Crée un cookie avec la clé et la valeur spécifiées pour le menu
    static setCookieMenu(key: string, value: string){
        if(key && value) {
            document.cookie = `${key}=${value};`
        }
    }
}
