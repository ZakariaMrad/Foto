import { Body, ResponseType, getClient } from "@tauri-apps/api/http";
import { Repository } from "./Repository";
import Complaint from "../models/Complaint";
import { APIError } from "../core/API/APIError";
import { JWTToken } from "../models/JWTToken";
import UserBlockPost from "../models/UserBlockPost";
import Account from "../models/Account";
import UserBlock from "../models/UserBlock";

const client = await getClient();

class UserBlockRepository extends Repository {

    async searchUser(search: any) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.get(`${this.url}/userblock/search?jwtToken=${jwt.data.jwtToken}&searchValue=${search}`, { responseType: ResponseType.JSON });
            if (search == '') return { data: [], success: true };
            let data = response.data as any;

            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                console.log(data);
                
                return { data: data.blockedUsers as UserBlock[], success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }

    public async blockUser(reason: string, idAccount: number) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        let userBlockPost = new UserBlockPost();
        userBlockPost.jwtToken = jwt.data.jwtToken;
        userBlockPost.reason = reason;
        userBlockPost.idUser = idAccount;
        console.log(userBlockPost);

        try {
            const response = await client.post(`${this.url}/userblock`, Body.json(userBlockPost), { responseType: ResponseType.JSON });
            let data = response.data as JWTToken;
            console.log(response.data);

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

    public async getComplaints() {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.get(`${this.url}/complaints?jwtToken=${jwt.data.jwtToken}`, { responseType: ResponseType.JSON });
            let data = response.data as any;

            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                return { data: data.complaints as Complaint[], success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }

    public async deleteUserBlock(idUserBlock: number) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.delete(`${this.url}/userblock/${idUserBlock}?jwtToken=${jwt.data.jwtToken}`, { responseType: ResponseType.JSON });
            let data = response.data as any;
            console.log(data);

            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                return { data: data.complaints as UserBlock[], success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }

    public async processComplaint(idComplaint: number) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.post(`${this.url}/complaint/${idComplaint}/process`, Body.json(jwt.data), { responseType: ResponseType.JSON });
            let data = response.data as JWTToken;
            console.log(response.data);

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
    public async readComplaint(idComplaint: number) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.post(`${this.url}/complaint/${idComplaint}/read`, Body.json(jwt.data), { responseType: ResponseType.JSON });
            let data = response.data as JWTToken;
            console.log(response.data);

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

}
export default new UserBlockRepository();