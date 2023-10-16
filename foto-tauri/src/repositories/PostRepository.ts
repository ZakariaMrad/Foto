import { Body, ResponseType, getClient } from "@tauri-apps/api/http";
import { Repository } from "./Repository";
import Post from "../models/Post";
import { APIResult } from "../core/API/APIResult";
import { JWTToken } from "../models/JWTToken";
import { APIError } from "../core/API/APIError";


const client = await getClient();
const url = 'http://localhost:8000';
class PostRepository extends Repository {
    public async createPost(newPost: Post): Promise<APIResult<JWTToken>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        newPost.jwtToken = jwt.data.jwtToken;
        try {
            const response = await client.post(`${url}/post`, Body.json(newPost), { responseType: ResponseType.JSON });
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

export default new PostRepository();