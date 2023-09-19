import { LoginAccount } from '../models/LoginAccount';
import { JWTToken } from '../models/JWTToken';
import { APIResult } from '../core/APIResult';
import { getClient, Body, ResponseType } from '@tauri-apps/api/http';
import { FormObject } from '../models/FormObject';

//TODO: remove the hard coded url

const url = 'http://localhost:8000';
const client = await getClient();

class AccountRepository {

    public async login(loginAccount: LoginAccount): Promise<APIResult<JWTToken>> {

        try {
            const csrfToken = await this.getCSRFToken(`${url}/account/login`)
            if (!csrfToken.success) return { errors: csrfToken.errors, success: false };
            loginAccount._token = csrfToken.data._token;
            console.log(loginAccount);
            

            const response = await client.post(`${url}/account/login`, Body.json(loginAccount),{ responseType: ResponseType.JSON });
            console.log(response);
            

            //if (response.status === 200) return { data: response.data, success: true };
            // If there is an unexpected response or error status code, return an Error object
            return { errors: response.data, success: false };

        } catch (error) {
            // Handle any network or request-related errors here and return an Error object
            return { errors: 'Network error or request failed', success: false };
        }
    }

    private async getCSRFToken(path: string): Promise<APIResult<FormObject>> {
        try {
            const response = await client.post(`${path}?need-csrf-token=true`, Body.json({}), { responseType: ResponseType.JSON });
            let data = response.data as FormObject;
            console.log(data);
            
            if (response.status === 200) return { data: data, success: true };
            // If there is an unexpected response or error status code, return an Error object
            
            return { errors: response.data, success: false };

        } catch (error) {
            // Handle any network or request-related errors here and return an Error object
            return { errors: error, success: false };
        }
    }

}
export default new AccountRepository();