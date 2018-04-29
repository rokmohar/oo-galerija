import { autoinject } from 'aurelia-framework';
import { Router } from 'aurelia-router';
import { Image } from '../../../models';
import { ImageApi, FileService } from '../../../services';

interface RouteParams {
    galleryId: string;
    imageId: string;
}

@autoinject()
export class EditableVM {

    private tagsOptions = { tags: true };

    private fileInput:  HTMLInputElement;
    private imgPreview: HTMLImageElement;

    private params:   RouteParams;
    private image:    Image;
    private isSaving: boolean;

    constructor(private imageApi: ImageApi, private fileService: FileService, private router: Router) {}

    private async activate(params: RouteParams): Promise<void> {
        this.params = params;

        // Reload or create image entity
        params.imageId ? await this.reloadImage() : this.createImage();
    }

    private async reloadImage(): Promise<void> {
        this.image = await this.imageApi.findImage(this.params.imageId);
    }

    private createImage(): void {
        this.image = new Image();
    }

    private async selectImage(): Promise<void> {
        if (this.isSaving) {
            return;
        }

        const file = await this.fileService.chooseImage(this.fileInput);

        this.isSaving = true;

        const content = await this.fileService.convertFileToDataURL(file);
        Object.assign(this.imgPreview, { src: content });
        Object.assign(this.image, { content });

        this.isSaving = false;
    }

    private async submitImage(): Promise<void> {
        if (this.isSaving) {
            return;
        }

        this.isSaving = true;

        const imageId   = this.image.id;
        const galleryId = this.params.galleryId;

        this.image = imageId
            ? await this.imageApi.changeImage(this.image)
            : await this.imageApi.createImage(galleryId, this.image);

        this.isSaving = false;

        this.router.navigateToRoute('admin.images', { galleryId });
    }

}
