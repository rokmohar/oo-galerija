import { autoinject } from 'aurelia-framework';
import { Router } from 'aurelia-router';
import { Gallery } from '../../../models';
import { GalleryApi } from '../../../services';

interface RouteParams {
    galleryId: string;
}

@autoinject()
export class EditableVM {

    private params:   RouteParams;
    private gallery:  Gallery;
    private isSaving: boolean;

    constructor(private galleryApi: GalleryApi, private router: Router) {}

    private async activate(params: RouteParams): Promise<void> {
        this.params = params;

        params.galleryId
            ? await this.reloadGallery()
            : this.createGallery();
    }

    private async submitGallery(): Promise<void> {
        this.isSaving = true;

        this.gallery = this.gallery.id
            ? await this.galleryApi.changeGallery(this.gallery)
            : await this.galleryApi.createGallery(this.gallery);

        this.isSaving = false;

        this.router.navigateToRoute('admin.galleries');
    }

    private async reloadGallery(): Promise<void> {
        this.gallery = await this.galleryApi.findGallery(this.params.galleryId);
    }

    private createGallery(): void {
        this.gallery = new Gallery();
    }

}
