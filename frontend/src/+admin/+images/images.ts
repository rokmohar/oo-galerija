import { autoinject } from 'aurelia-framework';
import { Router, RouterConfiguration } from 'aurelia-router';
import { PLATFORM } from 'aurelia-pal';

@autoinject()
export class ImagesVM {

    private router: Router;

    private async configureRouter(config: RouterConfiguration, router: Router): Promise<void> {
        config.map([
            {
                route:    '',
                name:     'admin.images.index',
                title:    'Seznam fotografij',
                moduleId: PLATFORM.moduleName('./+listing/listing'),
                auth:     true,
            },
            {
                route:    'create',
                name:     'admin.images.create',
                title:    'Nova fotografija',
                moduleId: PLATFORM.moduleName('./+editable/editable'),
                auth:     true,
            },
            {
                route:    ':imageId',
                href:     'imageId',
                name:     'admin.images.preview',
                title:    'Predogled fotografije',
                moduleId: PLATFORM.moduleName('./+preview/preview'),
                auth:     true,
            },
            {
                route:    ':imageId/edit',
                href:     'imageId',
                name:     'admin.images.edit',
                title:    'Uredi fotografijo',
                moduleId: PLATFORM.moduleName('./+editable/editable'),
                auth:     true,
            },
            {
                route:    ':imageId1/:imageId2',
                href:     'imageId1',
                name:     'admin.images.compare',
                title:    'Primerjaj fotografije',
                moduleId: PLATFORM.moduleName('./+compare/compare'),
                auth:     true,
            },
        ]);

        this.router = router;
    }

}
