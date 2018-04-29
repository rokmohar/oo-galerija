import { autoinject } from 'aurelia-framework';
import { Image } from '../../../models';
import { ImageApi } from '../../../services';

interface RouteParams {
    galleryId: string;
    imageId:   string;
}

@autoinject()
export class PreviewVM {

    private params: RouteParams;
    private image:  Image;

    constructor(private imageApi: ImageApi) {}

    private async activate(params: RouteParams): Promise<void> {
        this.params = params;
        this.image = await this.imageApi.findImage(this.params.imageId);
    }

}

