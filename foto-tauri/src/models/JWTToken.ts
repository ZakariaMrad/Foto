import { APIResponse } from "../core/API/APIResponse";

export class JWTToken implements APIResponse{
    jwtToken?:string = '';
    message?: string;
}