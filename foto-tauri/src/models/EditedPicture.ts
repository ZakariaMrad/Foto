export default class EditedPicture {
    name: string;
    description: string = "";
    base64: string;
    exposition: number = 100;
    contrast: number = 100;
    saturation: number = 100;

    constructor(name: string, base64: string)
    {
        this.name = name;
        this.base64 = base64;
    }
}