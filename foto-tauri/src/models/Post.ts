import { APIResponse } from "../core/API/APIResponse";
import Account from "./Account";
import Foto from "./Foto";
import { JWTToken } from "./JWTToken";

export default class Post extends JWTToken implements APIResponse{
    idPost: number = 0;
    owner: Account = new Account();
    description: string = '';
    creationDate: Date = new Date();
    foto:Foto = new Foto();
    
}