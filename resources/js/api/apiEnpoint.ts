import axiosInstance from './axiosInstance';

export class ApiEndpoint {
    public async handleGetMainMenu() {
        return await axiosInstance.get(`mainMenu`);
    }
}



