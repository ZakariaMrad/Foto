import { Body, ResponseType, getClient } from "@tauri-apps/api/http";
import { APIResult } from "../core/API/APIResult";
import { Repository } from "./Repository";
import { JWTToken } from "../models/JWTToken";
import { APIError } from "../core/API/APIError";
import Album from "../models/Album";

const client = await getClient();

class AlbumRepository extends Repository {
    public async createAlbum(album: Album): Promise<APIResult<JWTToken>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        album.jwtToken = jwt.data.jwtToken;
        try {
            const response = await client.post(`${this.url}/album`, Body.json(album), { responseType: ResponseType.JSON });
            let data = response.data as JWTToken;
            console.log('i get here', response.data);
            
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
    public async modifyAlbum(album: Album): Promise<APIResult<JWTToken>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        album.jwtToken = jwt.data.jwtToken;
        try {
            const response = await client.post(`${this.url}/modifyalbum/${album.idAlbum}`, Body.json(album), { responseType: ResponseType.JSON });
            console.log(response.data);
            
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
    public async deleteAlbum(idAlbum:number): Promise<APIResult<JWTToken>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        
        try {                        
            const response = await client.delete(`${this.url}/album/${idAlbum}?jwtToken=${jwt.data.jwtToken}`, { responseType: ResponseType.JSON });
            let data = response.data as any;      
            console.log(data);
            
            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);

                return { data: data, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }
    
    public async getAlbums(): Promise<APIResult<Album[]>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        
        try {                        
            const response = await client.get(`${this.url}/albums?jwtToken=${jwt.data.jwtToken}`, { responseType: ResponseType.JSON });
            let data = response.data as any;      
            
            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);

                return { data: data.albums as Album[], success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }
}
export default new AlbumRepository();