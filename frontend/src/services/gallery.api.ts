import { autoinject } from 'aurelia-framework';
import { HttpService } from './http.service';
import { Gallery } from '../models';

@autoinject()
export class GalleryApi {

    constructor(private httpService: HttpService) {}

    public async findAllGalleries(): Promise<Gallery[]> {
        const url      = `/galleries`;
        const response = await this.httpService.get(url);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

    public async createGallery(gallery: Gallery): Promise<Gallery> {
        const url      = `/gallery`;
        const response = await this.httpService.postAuthorized(url, gallery);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

    public async findGallery(galleryId: string): Promise<Gallery> {
        const url      = `/gallery/${galleryId}`;
        const response = await this.httpService.get(url);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

    public async deleteGallery(galleryId: string): Promise<void> {
        const url      = `/gallery/${galleryId}`;
        const response = await this.httpService.deleteAuthorized(url);

        if (response.status !== 200) {
            return Promise.reject(response);
        }
    }

    public async changeGallery(gallery: Gallery): Promise<Gallery> {
        const url      = `/gallery/${gallery.id}`;
        const response = await this.httpService.postAuthorized(url, gallery);

        if (response.status === 200) {
            return response.json();
        }

        return Promise.reject(response);
    }

}
