import axiosInstance from './axiosInstance';

export class ApiEndpoint {
    public async handleGetMainMenu() {
        return await axiosInstance.get(`mainMenu`);
    }
    public async handleGetSecondaryMenu(media: string) {
        return await axiosInstance.get(`secondaryMenu?media=${media}`);
    }
}



