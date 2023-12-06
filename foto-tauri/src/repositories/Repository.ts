import { APIError } from "../core/API/APIError";
import { APIResult } from "../core/API/APIResult";
import { JWTToken } from "../models/JWTToken";
import AccountDatastore from "./datastore/AccountDatastore";

export class Repository {
    protected url = 'https://foto.1839783.techinfo-cstj.ca';
    // protected url = 'http://localhost:8000';

    protected getAPIError(errors: any): APIError[] {
        let keys = Object.keys(errors);
        let errorsArray: APIError[] = [];
        keys.forEach(key => {

            errorsArray.push(new APIError(key, errors[key][0], key == 'message'));
        });
        return errorsArray;
    }
    protected async handleJWT(data: JWTToken) {
        let jwtToken = new JWTToken();
        jwtToken.jwtToken = data.jwtToken;
        await AccountDatastore.setJWTToken(jwtToken);
    }

    protected async getJWTToken(): Promise<APIResult<JWTToken>> {
        try {
            let jwtToken = await AccountDatastore.getJWTToken();
            if (!jwtToken) return { errors: [new APIError('JWT', 'Token not found.')], success: false };
            return { data: jwtToken, success: true };
        } catch (error) {
            // Handle any network or request-related errors here and return an Error object
            return { errors: error as APIError[], success: false };
        }
    }

}