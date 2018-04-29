import { autoinject } from 'aurelia-framework';
import { User } from '../../models';
import { UserApi, StorageService } from '../../services';

@autoinject()
export class SettingsVM {

    private isSaving: boolean;

    constructor(private userApi: UserApi, private storageService: StorageService) {}

    private get identity(): User {
        return this.storageService.getIdentity();
    }

    private async submitIdentity(): Promise<void> {
        this.isSaving = true;

        const identity = this.identity.id
            ? await this.userApi.changeUser(this.identity)
            : await this.userApi.createUser(this.identity);

        this.storageService.setIdentity(identity);

        this.isSaving = false;
    }

}
