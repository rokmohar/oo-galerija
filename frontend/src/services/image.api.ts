import { autoinject } from 'aurelia-framework';
import { HttpService } from './http.service';
import { Image } from '../models';

@autoinject()
export class ImageApi {

    constructor(private httpService: HttpService) {}

    public async findAllImages(galleryId: string): Promise<Image[]> {
        const url      = `/gallery/${galleryId}/images`;
        const response = await this.httpService.get(url);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

    public async createImage(galleryId: string, image: Image): Promise<Image> {
        const url      = `/gallery/${galleryId}/image`;
        const response = await this.httpService.postAuthorized(url, image);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

    public async findImage(imageId: string): Promise<Image> {
        const url      = `/image/${imageId}`;
        const response = await this.httpService.get(url);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

    public async deleteImage(imageId: string): Promise<void> {
        const url      = `/image/${imageId}`;
        const response = await this.httpService.deleteAuthorized(url);

        if (response.status !== 200) {
            return Promise.reject(response);
        }
    }

    public async changeImage(image: Image): Promise<Image> {
        const url      = `/image/${image.id}`;
        const response = await this.httpService.postAuthorized(url, image);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

}
