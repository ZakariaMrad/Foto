import { APIResponse } from "../core/API/APIResponse";
import { JWTToken } from "./JWTToken";

export default class Account extends JWTToken implements APIResponse{
    public idAccount: number = 0;
    public email: string = '';
    public name: string = '';
    public picturePath: string = '';
    public location: string = '';
    public bio: string = '';
    public birthDate: Date = new Date();
    public creationDate: Date = new Date();
    public friends: number[]= [];
    declare public message?: string;

    
}