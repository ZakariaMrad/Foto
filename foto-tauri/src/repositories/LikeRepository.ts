import { Body, ResponseType, getClient } from "@tauri-apps/api/http";
import Like from "../models/Like";
import { Repository } from "./Repository";
import { APIError } from "../core/API/APIError";

const client = await getClient();

class LikeRepository extends Repository {
    public async uploadLike(like: Like) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        like.jwtToken = jwt.data.jwtToken;
        try {
            const response = await client.post(`${this.url}/likes`, Body.json(like),{ responseType: ResponseType.JSON });
            console.log(response.data);
            
            if (response.status === 200) {
                return { success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            console.log(error);
            return { errors: error as [APIError], success: false };
        }
    }

    public async getLike(idUser: number, idPost: number) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        try {
            const response = await client.get(`${this.url}/likes?jwtToken=${jwt.data.jwtToken}&idUser=${idUser}&idPost=${idPost}`,{ responseType: ResponseType.JSON });
            console.log(response.data);
            const data = response.data as any;
            
            if (response.status === 200) {
                return { data: data.like as Like, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            console.log(error);
            return { errors: error as [APIError], success: false };
        }
    }

    public async deleteLike(idLike: number) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        try {
            const response = await client.delete(`${this.url}/likes/${idLike}?jwtToken=${jwt.data.jwtToken}`,{ responseType: ResponseType.JSON });
            console.log(response.data);
            
            if (response.status === 200) {
                return { success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            console.log(error);
            return { errors: error as [APIError], success: false };
        }
    }
}
export default new LikeRepository();