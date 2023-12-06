import { Body, ResponseType, getClient } from "@tauri-apps/api/http";
import Comment from "../models/Comment";
import { Repository } from "./Repository";
import { APIError } from "../core/API/APIError";

const client = await getClient();

class CommentRepository extends Repository {
    public async uploadComments(comment: Comment) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        comment.jwtToken = jwt.data.jwtToken;
        try {
            const response = await client.post(`${this.url}/comments`, Body.json(comment),{ responseType: ResponseType.JSON });
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
export default new CommentRepository();