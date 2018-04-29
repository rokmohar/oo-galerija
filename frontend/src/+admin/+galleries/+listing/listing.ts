import { autoinject } from 'aurelia-framework';
import { Gallery } from '../../../models';
import { GalleryApi } from '../../../services';

@autoinject()
export class ListingVM {

    private galleries: Set<Gallery>;

    constructor(private galleryApi: GalleryApi) {}

    private async activate(): Promise<void> {
        this.galleries = new Set<Gallery>(await this.galleryApi.findAllGalleries());
    }

    private async deleteGallery(gallery: Gallery): Promise<void> {
        await this.galleryApi.deleteGallery(gallery.id);
        this.galleries.delete(gallery);
    }

}
