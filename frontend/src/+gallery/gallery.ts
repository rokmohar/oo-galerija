import { autoinject } from 'aurelia-framework';
import { Router } from 'aurelia-router';
import { GalleryApi, ImageApi } from '../services';
import { Gallery, Image } from '../models';

interface RouteParams {
    galleryId: string;
}

@autoinject()
export class GalleryVM {

    private params:  RouteParams;
    private gallery: Gallery;
    private images:  Set<Image>;

    constructor(private galleryApi: GalleryApi, private imageApi: ImageApi, private router: Router) {}

    private async activate(params: RouteParams): Promise<void> {
        this.params = params;

        this.gallery = await this.galleryApi.findGallery(params.galleryId);
        this.images  = new Set<Image>(await this.imageApi.findAllImages(params.galleryId));
    }

}
