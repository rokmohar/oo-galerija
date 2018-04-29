import { Router, RouterConfiguration } from 'aurelia-router';
import { PLATFORM } from 'aurelia-pal';
import { AnonymousStep, AuthenticateStep } from './pipelines';

export class AppVM {

    private router: Router;

    private async configureRouter(config: RouterConfiguration, router: Router): Promise<void> {
        Object.assign(config, { title: 'Galerija' });
        Object.assign(config.options, { pushState: true, root: '/' });

        config.addPipelineStep('authorize', AnonymousStep);
        config.addPipelineStep('authorize', AuthenticateStep);

        config.map([
            {
                route:    '',
                name:     'index',
                redirect: 'admin',
                auth:     true,
            },
            {
                route:    'admin',
                name:     'admin',
                title:    'Admin',
                moduleId: PLATFORM.moduleName('./+admin/admin'),
                auth:     true,
            },
            {
                route:    'gallery/:galleryId',
                href:     'galleryId',
                name:     'gallery',
                title:    'Galerija',
                moduleId: PLATFORM.moduleName('./+gallery/gallery'),
                auth:     true,
            },
            {
                route:    'image/:imageId',
                href:     'imageId',
                name:     'image',
                title:    'Fotografija',
                moduleId: PLATFORM.moduleName('./+image/image'),
                auth:     true,
            },
            {
                route:    'logout',
                name:     'logout',
                title:    'Odjava',
                moduleId: PLATFORM.moduleName('./+logout/logout'),
                auth:     true,
            },
            {
                route:    'login',
                name:     'login',
                title:    'Prijava',
                moduleId: PLATFORM.moduleName('./+login/login'),
                guest:    true,
            },
        ]);

        this.router = router;
    }

}
