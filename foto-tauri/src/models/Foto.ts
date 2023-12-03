import { APIResponse } from "../core/API/APIResponse";
import { JWTToken } from "./JWTToken";

export default class Foto extends JWTToken implements APIResponse{
    idFoto: number = 0;
    name: string = '';
    description: string = '';
    path?: string = '';
    base64image?: string = '';
    original64image?: string = '';
    originalPath?: string = '';
    isNSFW: boolean = false;
    saturation: number = 0;
    exposition: number = 0;
    contrast: number = 0;
    uploadDate? = {date: ""};
    declare message?: string | undefined;
}