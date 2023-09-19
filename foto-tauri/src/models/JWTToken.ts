import { APIResponse } from "../core/API/APIResponse";

export class JWTToken extends APIResponse {
    jwtToken:string = '';
    constructor(jwtToken: string) {
        super();
        this.jwtToken = jwtToken;
    }
}