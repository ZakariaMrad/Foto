import { LoginAccount } from '../models/LoginAccount';
import { JWTToken } from '../models/JWTToken';
import { APIResult } from '../core/API/APIResult';
import { getClient, Body, ResponseType } from '@tauri-apps/api/http';
import { APIError } from '../core/API/APIError';
import { Repository } from './Repository';
import AccountDatastore from './datastore/AccountDatastore';
import RegistraionAccount from '../models/RegistrationAccount';

//TODO: remove the hard coded url

const url = 'http://localhost:8000';
const client = await getClient();
class AccountRepository extends Repository {
    async logout() {
        await AccountDatastore.removeJWTToken();
    }

    public async login(loginAccount: LoginAccount): Promise<APIResult<JWTToken>> {
        try {
            console.log("login");
            console.log(loginAccount);
            
            
            const response = await client.post(`${url}/account/login`, Body.json(loginAccount), { responseType: ResponseType.JSON });
            let data = response.data as JWTToken;

            if (response.status === 200) {
                await AccountDatastore.setJWTToken(data);
                return { data: data, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };

        } catch (error) {
            // Handle any network or request-related errors here and return an Error object
            return { errors: error as [APIError], success: false };
        }
    }

    public async register(registrationAccount: RegistraionAccount): Promise<APIResult<JWTToken>> {
        try {
            console.log('register');
            console.log(registrationAccount);
            
            
            const response = await client.post(`${url}/account/register`, Body.json(registrationAccount), { responseType: ResponseType.JSON });
            console.log(response);
            
            let data = response.data as JWTToken;

            if (response.status === 200) {
                await AccountDatastore.setJWTToken(data);
                return { data: data, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };

        } catch (error) {
            // Handle any network or request-related errors here and return an Error object
            console.log(error);
            
            return { errors: error as [APIError], success: false };
        }
    }
    public async isConnected(): Promise<boolean> {
        let jtw = await AccountDatastore.getJWTToken()
        console.log(jtw);

        return !(!jtw || !jtw.jwtToken)
    }

    private async getJWTToken(): Promise<APIResult<JWTToken>> {
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


export default new AccountRepository();