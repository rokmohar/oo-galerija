import { autoinject } from 'aurelia-framework';
import { User } from '../models';
import { StorageService } from './storage.service';
import { HttpService } from './http.service';

@autoinject()
export class AuthService {

    private identityPromise: Promise<null | User>;

    constructor(private storageService: StorageService, private httpService: HttpService) {}

    public async getIdentity(): Promise<User> {
        const response = await this.httpService.getAuthorized(`/identity`);
        return response.json();
    }

    public async getJwt(username: string, password: string): Promise<string> {
        const response = await this.httpService.post(`/login`, { username, password });
        return response.text();
    }

    public async login(username: string, password: string): Promise<null | User> {
        const jwt = await this.getJwt(username, password);
        this.storageService.setJwtToken(jwt);

        return await this.getIdentity();
    }

    public async reloadIdentity(): Promise<null | User> {
        if (this.identityPromise) {
            return await this.identityPromise;
        }

        this.identityPromise = new Promise<null | User>((resolve, reject) => {
            const identity = this.getIdentity();
            identity.then((response) => resolve(response), (reason) => reject(reason));
        });

        const identity = await this.identityPromise;
        this.storageService.setIdentity(identity);

        this.identityPromise = null;

        return this.storageService.getIdentity();
    }

}
