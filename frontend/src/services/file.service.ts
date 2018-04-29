import { autoinject } from 'aurelia-framework';

export const IMAGE_EXTENSIONS = ['.png', '.gif', '.jpg', '.jpeg'];

@autoinject()
export class FileService {

    public async chooseFiles($input: HTMLInputElement, allowedTypes?: string): Promise<FileList> {
        return new Promise<FileList>((resolve, reject) => {
            $input.setAttribute('type', 'file');
            $input.setAttribute('accept', allowedTypes);
            $input.setAttribute('style', 'cursor: pointer;'); // SPN-1951

            const clickListener = () => {
                // Remove to avoid duplicates
                $input.removeEventListener('click', clickListener);
                $input.value = null;
            };

            const changeListener = () => {
                // Remove to avoid duplicates
                $input.removeEventListener('change', changeListener);
                $input.value.length === 0 ? reject('No file selected') : resolve($input.files);
            };

            $input.addEventListener('click', clickListener);
            $input.addEventListener('change', changeListener);
            $input.click();
        });
    }

    public async chooseFile($input: HTMLInputElement, allowedTypes?: string): Promise<File> {
        const fileList = await this.chooseFiles($input, allowedTypes);
        return fileList.item(0);
    }

    public async chooseImages($input: HTMLInputElement): Promise<FileList> {
        return this.chooseFiles($input, IMAGE_EXTENSIONS.join(','));
    }

    public async chooseImage($input: HTMLInputElement): Promise<File> {
        return this.chooseFile($input, IMAGE_EXTENSIONS.join(','));
    }

    public convertFileToDataURL(file: File): Promise<string> {
        return new Promise<string>((resolve, reject) => {
            const reader = new FileReader();
            reader.addEventListener('load', () => resolve(reader.result));
            reader.addEventListener('error', (ev: ErrorEvent) => reject(ev.error));
            reader.readAsDataURL(file);
        });
    }

    public convertFileToBinaryString(file: File): Promise<string> {
        return new Promise<string>((resolve, reject) => {
            const reader = new FileReader();

            reader.addEventListener('load', () => {
                resolve(reader.result);
            });

            reader.addEventListener('error', (ev: ErrorEvent) => {
                reject(ev.error);
            });

            reader.readAsBinaryString(file);
        });
    }

}