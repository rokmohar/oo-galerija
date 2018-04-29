import { autoinject } from 'aurelia-framework';
import { Image } from '../../../models';
import { ImageApi } from '../../../services';

import 'jimp/browser/lib/jimp';
const jimp = window.Jimp;

interface RouteParams {
    imageId1: string;
    imageId2: string;
}

@autoinject()
export class CompareVM {

    private imgDiff: string;

    private params: RouteParams;
    private image1: Image;
    private image2: Image;

    constructor(private imageApi: ImageApi) {}

    private async activate(params: RouteParams): Promise<void> {
        this.params = params;
        this.image1 = await this.imageApi.findImage(params.imageId1);
        this.image2 = await this.imageApi.findImage(params.imageId2);

        const img1 = await jimp.read(this.image1.web_path);
        const img2 = await jimp.read(this.image2.web_path);

        const diff = jimp.diff(img1, img2, 0.1);
        diff.image.getBase64(jimp.MIME_PNG, (err, data) => { this.imgDiff = data; });
    }

}
