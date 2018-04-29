import { autoinject } from 'aurelia-framework';
import { HttpClient, json } from "aurelia-fetch-client";
import { StorageService } from './storage.service';

@autoinject()
export class HttpService {

    private httpClient: HttpClient;
    
    constructor(private storageService: StorageService) {
        this.httpClient = new HttpClient();
        this.httpClient.configure((config) => {
            config.withBaseUrl(`/api`)
        });
    }

    /**
     * Sends GET request.
     */
    public async get(url: string): Promise<Response> {
        return await this.httpClient.fetch(url, {
            method: 'GET',
        });
    }

    /**
     * Sends authorized GET request.
     */
    public async getAuthorized(url: string): Promise<Response> {
        const jwt = this.storageService.getJwtToken();
        return await this.httpClient.fetch(url, {
            method:  'GET',
            headers: { 'Authorization': `Bearer ${jwt}` }
        });
    }

    /**
     * Sends DELETE request.
     */
    public async delete(url: string): Promise<Response> {
        return await this.httpClient.fetch(url, {
            method: 'DELETE',
        });
    }

    /**
     * Sends authorized DELETE request.
     */
    public async deleteAuthorized(url: string): Promise<Response> {
        const jwt = this.storageService.getJwtToken();
        return await this.httpClient.fetch(url, {
            method:  'DELETE',
            headers: { 'Authorization': `Bearer ${jwt}` }
        });
    }

    /**
     * Sends POST request.
     */
    public async post(url: string, body?: any): Promise<Response> {
        return await this.httpClient.fetch(url, {
            method: 'POST',
            body:   json(body),
        });
    }

    /**
     * Sends authorized POST request.
     */
    public async postAuthorized(url: string, body?: any): Promise<Response> {
        const jwt = this.storageService.getJwtToken();
        return await this.httpClient.fetch(url, {
            method:  'POST',
            headers: { 'Authorization': `Bearer ${jwt}` },
            body:    json(body),
        });
    }

}
