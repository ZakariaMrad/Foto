import { Body, ResponseType, getClient } from "@tauri-apps/api/http";
import { APIResult } from "../core/API/APIResult";
import Foto from "../models/Foto";
import { Repository } from "./Repository";
import { JWTToken } from "../models/JWTToken";
import { APIError } from "../core/API/APIError";
import Album from "../models/Album";

const client = await getClient();
const url = 'http://localhost:8000';

class FotoRepository extends Repository {
    public async createAlbum(album: Album): Promise<APIResult<JWTToken>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        album.jwtToken = jwt.data.jwtToken;
        try {
            const response = await client.post(`${url}/album`, Body.json(album), { responseType: ResponseType.JSON });
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
}
export default new FotoRepository();