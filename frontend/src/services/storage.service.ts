import { autoinject } from 'aurelia-framework';
import { User } from '../models';
import { BehaviorSubject } from 'rxjs/index';

@autoinject()
export class StorageService {

    private readonly identity: BehaviorSubject<null | User>;
    private readonly jwtToken: BehaviorSubject<null | string>;

    constructor() {
        const jwtToken = localStorage.getItem('jwt_token');

        this.identity = new BehaviorSubject<null | User>(null);
        this.jwtToken = new BehaviorSubject<null | string>(jwtToken);
    }

    public getIdentitySubject(): BehaviorSubject<null | User> {
        return this.identity;
    }

    public getIdentity(): null | User {
        return this.identity.getValue();
    }

    public setIdentity(identity: null | User) {
        this.identity.next(identity);
    }

    public getJwtTokenSubject(): BehaviorSubject<null | string> {
        return this.jwtToken;
    }

    public getJwtToken(): null | string {
        return this.jwtToken.getValue();
    }

    public setJwtToken(jwtToken: null | string) {
        this.jwtToken.next(jwtToken);

        jwtToken
            ? localStorage.setItem('jwt_token', jwtToken)
            : localStorage.removeItem('jwt_token');
    }

}