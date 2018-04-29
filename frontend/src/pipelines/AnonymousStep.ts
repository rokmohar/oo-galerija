import { autoinject } from 'aurelia-framework';
import { NavigationInstruction, Next, Redirect } from 'aurelia-router';
import { User } from '../models';
import { AuthService, StorageService } from '../services';

@autoinject()
export class AnonymousStep {

    constructor(private storageService: StorageService, private authService: AuthService) {}

    /**
     * Is executed when instruction is run.
     */
    private async run(instruction: NavigationInstruction, next: Next): Promise<any> {
        // Reverse instructions so that child-most is the first one
        let instructions = instruction.getAllInstructions().reverse();

        for (let index = 0; index < instructions.length; index++) {
            // Check if anonymous user is required
            if (instructions[index].config.guest === true) {
                // Retrieve identity or try to reload it
                const identity = this.identity || this.jwtToken && await this.reloadIdentity();

                if (identity) {
                    // User is logged-in
                    return next.cancel(new Redirect(`/`));
                }

                return next();
            }
        }

        return next();
    }

    /**
     * Returns active identity.
     */
    private get identity(): User {
        return this.storageService.getIdentity();
    }

    /**
     * Returns JWT token.
     */
    private get jwtToken(): string {
        return this.storageService.getJwtToken();
    }

    /**
     * Reloads identity from the API server.
     */
    private async reloadIdentity(): Promise<User> {
        return this.authService.reloadIdentity();
    }

}
