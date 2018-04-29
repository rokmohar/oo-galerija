import { autoinject } from 'aurelia-framework';
import { Router } from 'aurelia-router';
import { User } from '../../../models';
import { UserApi } from '../../../services';

interface RouteParams {
    userId: string;
}

@autoinject()
export class EditableVM {

    private params:   RouteParams;
    private user:     User;
    private isSaving: boolean;

    constructor(private userApi: UserApi, private router: Router) {}

    private async activate(params: RouteParams): Promise<void> {
        this.params = params;

        params.userId
            ? await this.reloadUser()
            : this.createUser();
    }

    private async submitUser(): Promise<void> {
        this.isSaving = true;

        this.user = this.user.id
            ? await this.userApi.changeUser(this.user)
            : await this.userApi.createUser(this.user);

        this.isSaving = false;

        this.router.navigateToRoute('admin.users');
    }

    private async reloadUser(): Promise<void> {
        this.user = await this.userApi.findUser(this.params.userId);
    }

    private createUser(): void {
        this.user = new User();
    }

}
