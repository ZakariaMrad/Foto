import { APIResponse } from "../core/API/APIResponse";
import { JWTToken } from "./JWTToken";

export default class Post extends JWTToken implements APIResponse{
    idPost: number = 0;
    title: string = '';
    description: string = '';
    id : number = 0;
    isPublic: boolean = false;
    isFoto: boolean = true;
}