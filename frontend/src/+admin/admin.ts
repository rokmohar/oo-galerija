import { Router, RouterConfiguration } from 'aurelia-router';
import { PLATFORM } from 'aurelia-pal';

export class AdminVM {

    private router: Router;

    private async configureRouter(config: RouterConfiguration, router: Router): Promise<void> {
        config.map([
            {
                route:    '',
                name:     'admin.index',
                nav:      true,
                auth:     true,
                redirect: 'galleries',
            },
            {
                route:    'galleries',
                name:     'admin.galleries',
                moduleId: PLATFORM.moduleName('./+galleries/galleries'),
                nav:      true,
                auth:     true,
            },
            {
                route:    'galleries/:galleryId/images',
                href:     'galleryId',
                name:     'admin.images',
                moduleId: PLATFORM.moduleName('./+images/images'),
                nav:      false,
                auth:     true,
            },
            {
                route:    'users',
                name:     'admin.users',
                moduleId: PLATFORM.moduleName('./+users/users'),
                nav:      true,
                auth:     true,
            },
            {
                route:    'settings',
                name:     'admin.settings',
                title:    'Nastavitve',
                moduleId: PLATFORM.moduleName('./+settings/settings'),
                nav:      true,
                auth:     true,
            },
        ]);

        this.router = router;
    }

}
