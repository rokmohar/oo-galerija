import { autoinject } from 'aurelia-framework';
import { Router } from 'aurelia-router';
import { AuthService } from '../services';

@autoinject()
export class LoginVM {

    private isLoading: boolean;
    private message:   string;

    private username: string;
    private password: string;

    constructor(private authService: AuthService, private router: Router) {}

    private async doLogin(): Promise<void> {
        try {
            this.isLoading = true;
            this.message   = null;

            await this.authService.login(this.username, this.password);

            this.isLoading = false;
        } catch (e) {
            this.isLoading = false;
            this.message   = 'Prijava ni bila uspešna.';
            return;
        }

        this.router.navigateToRoute('index');
    }

}
