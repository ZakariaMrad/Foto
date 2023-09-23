import { LoginAccount } from '../models/LoginAccount';
import { JWTToken } from '../models/JWTToken';
import { APIResult } from '../core/API/APIResult';
import { getClient, Body, ResponseType } from '@tauri-apps/api/http';
import { APIError } from '../core/API/APIError';
import { Repository } from './Repository';
import AccountDatastore from './datastore/AccountDatastore';
import RegistraionAccount from '../models/RegistrationAccount';
import { Account } from '../models/Account';

//TODO: remove the hard coded url

const url = 'http://localhost:8000';
const client = await getClient();
class AccountRepository extends Repository {
    async logout() {
        await AccountDatastore.removeJWTToken();
    }

    public async login(loginAccount: LoginAccount): Promise<APIResult<JWTToken>> {
        try {
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
            const response = await client.post(`${url}/account/register`, Body.json(registrationAccount), { responseType: ResponseType.JSON });
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

        return (!!jtw)
    }

    public async getAccount(): Promise<APIResult<Account>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.post(`${url}/account`, Body.json(jwt.data), { responseType: ResponseType.JSON });
            let data = response.data as Account;

            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                
                return { data: data, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            console.log(error);
            return { errors: error as [APIError], success: false };
        }


    }

    
}


export default new AccountRepository();