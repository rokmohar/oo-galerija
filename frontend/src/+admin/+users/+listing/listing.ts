import { autoinject } from 'aurelia-framework';
import { UserApi } from '../../../services';
import { User } from '../../../models';

@autoinject()
export class ListingVM {

    private users: Set<User>;

    constructor(private userApi: UserApi) {}

    private async activate(): Promise<void> {
        this.users = new Set<User>(await this.userApi.findAllUsers());
    }

    private async deleteUser(user: User): Promise<void> {
        await this.userApi.deleteUser(user.id);
        this.users.delete(user);
    }

}
