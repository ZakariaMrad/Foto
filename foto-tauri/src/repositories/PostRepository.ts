import { Body, ResponseType, getClient } from "@tauri-apps/api/http";
import { Repository } from "./Repository";
import Post from "../models/Post";
import { APIResult } from "../core/API/APIResult";
import { JWTToken } from "../models/JWTToken";
import { APIError } from "../core/API/APIError";
import AccountRepository from "./AccountRepository";
import Account from "../models/Account";


const client = await getClient();
class PostRepository extends Repository {
    public async createPost(newPost: Post): Promise<APIResult<JWTToken>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        newPost.jwtToken = jwt.data.jwtToken;
        try {
            const response = await client.post(`${this.url}/post`, Body.json(newPost), { responseType: ResponseType.JSON });
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

    public async getPosts(): Promise<APIResult<Post[]>> {
        // let jwt = await this.getJWTToken();
        // if (!jwt.success) return { errors: jwt.errors, success: false };
        //?Was In URl ?jwtToken=${jwt.data.jwtToken}

        let result = await AccountRepository.getAccount(); 
        let connectedAccount: Account | undefined = undefined;
        if (result.success) connectedAccount = result.data;
        try {
            const response = await client.get(`${this.url}/posts`, { responseType: ResponseType.JSON });
            let data = response.data as any;
            console.log(data);

            data.posts.forEach( (post: Post) => {
                post.isLiked = false;
                post.likes.forEach(like => {
                    if (connectedAccount) {
                        if (like.user.idAccount === connectedAccount.idAccount)
                        post.isLiked = true;
                    }
                })
            });

            if (response.status === 200) {
                // this.handleJWT(response.data as JWTToken);

                return { data: data.posts as Post[], success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }

    public async getPostById(idPost: number): Promise<APIResult<Post>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.get(`${this.url}/posts/${idPost}?jwtToken=${jwt.data.jwtToken}`, { responseType: ResponseType.JSON });
            let data = response.data as any;
            
            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);

                return { data: data.post as Post, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }
}

export default new PostRepository();