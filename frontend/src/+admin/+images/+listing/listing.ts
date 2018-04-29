import { autoinject } from 'aurelia-framework';
import { Router } from 'aurelia-router';
import { GalleryApi, ImageApi } from '../../../services';
import { Gallery, Image } from '../../../models';

interface RouteParams {
    galleryId: string;
}

@autoinject()
export class ListingVM {

    private params:  RouteParams;
    private gallery: Gallery;
    private images:  Set<Image>;

    private compare1: Image;
    private compare2: Image;

    constructor(private galleryApi: GalleryApi, private imageApi: ImageApi, private router: Router) {}

    private async activate(params: RouteParams): Promise<void> {
        this.params = params;

        this.gallery = await this.galleryApi.findGallery(params.galleryId);
        this.images  = new Set<Image>(await this.imageApi.findAllImages(params.galleryId));
    }

    private async deleteImage(image: Image): Promise<void> {
        await this.imageApi.deleteImage(image.id);
        this.images.delete(image);
    }

    private compareImages(): void {
        if (!this.compare1 || !this.compare2 || this.compare1 === this.compare2) {
            return;
        }

        const imageId1 = this.compare1.id;
        const imageId2 = this.compare2.id;
        this.router.navigateToRoute('admin.images.compare', { imageId1, imageId2 });
    }

}
