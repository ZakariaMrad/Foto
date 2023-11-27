import { APIResponse } from "../core/API/APIResponse";
import { JWTToken } from "./JWTToken";

export default class Foto extends JWTToken implements APIResponse{
    idFoto: number = 0;
    name: string = '';
    description: string = '';
    path?: string = '';
    base64image?: string = '';
    isNSFW: boolean = false;
    uploadDate? = {date: ""};
    declare message?: string | undefined;
}