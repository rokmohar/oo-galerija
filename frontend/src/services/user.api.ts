import { autoinject } from 'aurelia-framework';
import { HttpService } from './http.service';
import { User } from '../models';

@autoinject()
export class UserApi {

    constructor(private httpService: HttpService) {}

    public async findAllUsers(): Promise<User[]> {
        const url      = `/users`;
        const response = await this.httpService.get(url);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

    public async createUser(user: User): Promise<User> {
        const url      = `/user`;
        const response = await this.httpService.postAuthorized(url, user);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

    public async findUser(userId: string): Promise<User> {
        const url      = `/user/${userId}`;
        const response = await this.httpService.getAuthorized(url);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

    public async deleteUser(userId: string): Promise<void> {
        const url      = `/user/${userId}`;
        const response = await this.httpService.deleteAuthorized(url);

        if (response.status !== 200) {
            return Promise.reject(response);
        }
    }

    public async changeUser(user: User): Promise<User> {
        const url      = `/user/${user.id}`;
        const response = await this.httpService.postAuthorized(url, user);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

}
