import { autoinject } from 'aurelia-framework';
import { Image } from '../models';
import { ImageApi } from '../services';

interface RouteParams {
    imageId: string;
}

@autoinject()
export class ImageVM {

    private params: RouteParams;
    private image:  Image;

    constructor(private imageApi: ImageApi) {}

    private async activate(params: RouteParams): Promise<void> {
        this.params = params;
        this.image  = await this.imageApi.findImage(params.imageId);
    }

}
