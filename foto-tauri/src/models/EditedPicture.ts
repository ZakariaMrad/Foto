export default class EditedPicture {
    base64: string;
    exposition: number;
    contrast: number;
    saturation: number;

    constructor(base64: string, exposition: number, contrast: number, saturation: number)
    {
        this.base64 = base64;
        this.exposition = exposition;
        this.contrast = contrast;
        this.saturation = saturation;
    }
}