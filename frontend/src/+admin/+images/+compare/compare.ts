import { autoinject } from 'aurelia-framework';
import { Router } from 'aurelia-router';
import { GalleryApi, ImageApi } from '../../../services';
import { Gallery, Image } from '../../../models';

interface RouteParams {
    galleryId: string;
    imageId:   string;
}

@autoinject()
export class CompareVM {

    private orientation: string;
    private params:      RouteParams;
    private images:      Set<Image>;
    private gallery:     Gallery;

    private get selected(): Image {
        const images = Array.from(this.images.values());

        for (const image of images) {
            if (image.id === this.params.imageId) {
                return image;
            }
        }
    }

    private get others(): Array<Image> {
        const images = Array.from(this.images.values());
        const others = Array<Image>();

        for (const image of images) {
            if (image.id !== this.params.imageId) {
                images.push(image);
            }
        }

        return others;
    }

    private get isHorizontal(): boolean {
        return this.orientation === 'horizontal';
    }

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

    private minHeight(image1: Image, image2: Image): number {
        return Math.min(image1.height, image2.height);
    }

}