import { autoinject } from 'aurelia-framework';
import { Router, RouterConfiguration } from 'aurelia-router';
import { PLATFORM } from 'aurelia-pal';

@autoinject()
export class GalleriesVM {

    private router: Router;

    private async configureRouter(config: RouterConfiguration, router: Router): Promise<void> {
        config.map([
            {
                route:    '',
                name:     'admin.galleries.listing',
                title:    'Seznam galerij',
                moduleId: PLATFORM.moduleName('./+listing/listing'),
                auth:     true,
            },
            {
                route:    'create',
                name:     'admin.galleries.create',
                title:    'Nova galerija',
                moduleId: PLATFORM.moduleName('./+editable/editable'),
                auth:     true,
            },
            {
                route:    ':galleryId/edit',
                href:     'galleryId',
                name:     'admin.galleries.edit',
                title:    'Uredi galerijo',
                moduleId: PLATFORM.moduleName('./+editable/editable'),
                auth:     true,
            },
        ]);

        this.router = router;
    }

}
