import { Gallery } from './Gallery';
import { User } from './User';

export class Image {

    id?:          string;
    author?:      User;
    gallery?:     Gallery;
    title?:       string;
    description?: string;
    customer?:    string;
    tags?:        string[];
    mime_type?:   string;
    checksum?:    string;
    extension?:   string;
    path?:        string;
    web_path?:    string;
    size?:        number;
    width?:       number;
    height?:      number;
    created_at?:  string;
    updated_at?:  string;
    content?:     string;

}
