export default class EditedPicture {
    base64: string;
    exposition: number = 100;
    contrast: number = 100;
    saturation: number = 100;

    constructor(base64: string)
    {
        this.base64 = base64;
    }
}