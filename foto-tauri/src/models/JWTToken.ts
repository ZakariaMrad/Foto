import { APIResponse } from "../core/API/APIResponse";

export class JWTToken implements APIResponse{
    jwtToken:string = '';
    message?: string;
    constructor(jwtToken: string) {
        this.jwtToken = jwtToken;
    }
}