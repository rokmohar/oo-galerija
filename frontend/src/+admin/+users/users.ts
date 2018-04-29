import { autoinject } from 'aurelia-framework';
import { Router, RouterConfiguration } from 'aurelia-router';
import { PLATFORM } from 'aurelia-pal';

@autoinject()
export class UsersVM {

    private router: Router;

    private async configureRouter(config: RouterConfiguration, router: Router): Promise<void> {
        config.map([
            {
                route:    '',
                name:     'admin.users.listing',
                title:    'Seznam uporabnikov',
                moduleId: PLATFORM.moduleName('./+listing/listing'),
                auth:     true,
            },
            {
                route:    'create',
                name:     'admin.users.create',
                title:    'Nov uporabnik',
                moduleId: PLATFORM.moduleName('./+editable/editable'),
                auth:     true,
            },
            {
                route:    ':userId/edit',
                href:     'userId',
                name:     'admin.users.edit',
                title:    'Uredi uporabnika',
                moduleId: PLATFORM.moduleName('./+editable/editable'),
                auth:     true,
            },
        ]);

        this.router = router;
    }

}
