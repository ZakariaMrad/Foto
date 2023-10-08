import { ResponseType, getClient } from "@tauri-apps/api/http";
import { APIResult } from "../core/API/APIResult";
import Foto from "../models/Foto";
import { Repository } from "./Repository";
import { JWTToken } from "../models/JWTToken";
import { APIError } from "../core/API/APIError";

const client = await getClient();
const url = 'http://localhost:8000';

class FotoRepository extends Repository {

    public async getFotos(): Promise<APIResult<Foto[]>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        
        try {                        
            const response = await client.get(`${url}/fotos?jwtToken=${jwt.data.jwtToken}`, { responseType: ResponseType.JSON });
            let data = response.data as any;      
            
            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);

                return { data: data.fotos as Foto[], success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }
    public async getFotosById(idFotos: number[]): Promise<APIResult<Foto[]>> {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        
        try {  
                      
            const response = await client.get(`${url}/fotosbyid?jwtToken=${jwt.data.jwtToken}&idFotos=[${idFotos.toString()}]`, { responseType: ResponseType.JSON });
            let data = response.data as any;      
            
            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                console.log('foto by id',data.fotos);

                return { data: data.fotos as Foto[], success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }
}
export default new FotoRepository();