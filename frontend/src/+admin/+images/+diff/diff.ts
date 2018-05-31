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
export class DiffVM {

    private orientation:  string;
    private imgDiff:      string;
    private image1:       Image;
    private image2:       Image;
    private params:       RouteParams;

    private get minHeight(): number {
        return Math.min(this.image1.height, this.image2.height);
    }

    private get isHorizontal(): boolean {
        return this.orientation === 'horizontal';
    }

    constructor(private imageApi: ImageApi) {
        this.orientation = 'vertical';
    }

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
