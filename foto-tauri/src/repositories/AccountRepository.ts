import { LoginAccount } from '../models/LoginAccount';
import { JWTToken } from '../models/JWTToken';
import { APIResult } from '../core/API/APIResult';
import { getClient, Body, ResponseType } from '@tauri-apps/api/http';
import { APIError } from '../core/API/APIError';
import { Repository } from './Repository';
import AccountDatastore from './datastore/AccountDatastore';
import RegistraionAccount from '../models/RegistrationAccount';
import Account from '../models/Account';

//TODO: remove the hard coded this.url

const client = await getClient();
class AccountRepository extends Repository {

    async startConnectionFlow(func: (value: boolean) => void, interval: number) {
        while (true) {
            await new Promise((resolve) => setTimeout(resolve, interval)); // Wait for the specified interval
            func(await this.isConnected());
        }
    }

    async isAdmin(): Promise<APIResult<boolean>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        let APIResponse = await this.getAccount();
        if (!APIResponse.success) return { errors: APIResponse.errors, success: false };
        let idAccount = APIResponse.data.idAccount;
        try {
            const response = await client.get(`${this.url}/account/isAdmin/${idAccount}?jwtToken=${jwt.data.jwtToken}`, { responseType: ResponseType.JSON });
            let data = response.data as any;

            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                return { data: data.isAdmin, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }

    async searchUser(search: any) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.get(`${this.url}/account/search?jwtToken=${jwt.data.jwtToken}&searchValue=${search}`, { responseType: ResponseType.JSON });
            if (search == '') return { data: [], success: true };
            let data = response.data as any;

            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                return { data: data.accounts as Account[], success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }

    async logout() {
        await AccountDatastore.removeJWTToken();
    }

    public async login(loginAccount: LoginAccount): Promise<APIResult<JWTToken>> {
        try {
            const response = await client.post(`${this.url}/account/login`, Body.json(loginAccount), { responseType: ResponseType.JSON });
            let data = response.data as JWTToken;

            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
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
            const response = await client.post(`${this.url}/account/register`, Body.json(registrationAccount), { responseType: ResponseType.JSON });
            let data = response.data as JWTToken;

            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
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
        let account = await this.getAccount();
        if (!account.success) this.logout();
        return account.success;

    }

    public async getAccount(): Promise<APIResult<Account>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.post(`${this.url}/account`, Body.json(jwt.data), { responseType: ResponseType.JSON });
            let data = response.data as any;
            // console.log(data);

            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                if (!data.user?.friends)
                    data.user.friends = []
                return { data: data.user as Account, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            console.log(error);
            return { errors: error as [APIError], success: false };
        }
    }


    public async follow(friendUser: Account): Promise<APIResult<Account>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        // console.log(friendUser.idAccount);
        
        try {
            console.log('ca marche');

            const response = await client.post(`${this.url}/account/follow/${friendUser.idAccount}`, Body.json(jwt.data), { responseType: ResponseType.JSON });
            let data = response.data as any;
            // console.log(data);

            if (response.status === 200) {
                // console.log("ca marche");
                if (!data.user?.friends)
                    data.user.friends = []

                return { data: data.user as Account, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            console.log(error);
            return { errors: error as [APIError], success: false };
        }
    }


    public async unfollow(friendUser: Account): Promise<APIResult<Account>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        // console.log(friendUser.idAccount);
        
        try {
            console.log('ca marche');

            const response = await client.post(`${this.url}/account/unfollow/${friendUser.idAccount}`, Body.json(jwt.data), { responseType: ResponseType.JSON });
            let data = response.data as any;
            // console.log(data);

            if (response.status === 200) {
                // console.log("ca marche");
                if (!data.user?.friends)
                    data.user.friends = []

                return { data: data.user as Account, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            console.log(error);
            return { errors: error as [APIError], success: false };
        }
    }



    public async getOtherUserAccount(idUser: number): Promise<APIResult<Account>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.get(`${this.url}/account/find/${idUser}`, { responseType: ResponseType.JSON });
            let data = response.data as any;
            // console.log(data);

            if (response.status === 200) {
                // console.log("ca marche");
                return { data: data.user as Account, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            console.log(error);
            return { errors: error as [APIError], success: false };
        }
    }


    public async updateAccount(personnalInfo: Account, picture: string | ArrayBuffer): Promise<APIResult<Account>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        personnalInfo.jwtToken = jwt.data.jwtToken;
        try {
            const response = await client.post(`${this.url}/account/modify`, Body.json({...personnalInfo, "image": picture}));
            let data = response.data as any;

            console.log(data);

            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);

                return { data: { ...data.account, ...data.message } as Account, success: true };
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