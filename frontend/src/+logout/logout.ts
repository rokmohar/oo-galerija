import { autoinject } from 'aurelia-framework';
import { StorageService } from '../services';
import { Router } from 'aurelia-router';

@autoinject()
export class LogoutVM {

    constructor(private storageService: StorageService, private router: Router) {}

    private async activate(): Promise<void> {
        this.storageService.setJwtToken(null);
        this.storageService.setIdentity(null);
        this.router.navigateToRoute('login');
    }

}
