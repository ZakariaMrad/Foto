import { APIResponse } from "../core/API/APIResponse";

export default class Foto implements APIResponse{
    idFoto: number = 0;
    name: string = '';
    description: string = '';
    path: string = '';
    isNSFW: boolean = false;
    message?: string | undefined;
}