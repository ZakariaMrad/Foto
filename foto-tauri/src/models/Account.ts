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

    declare public message?: string;

    public constructor( name: string, picturePath: string, location: string, bio: string, birthDate: Date, creationDate: Date, email: string, jwtToken: string){
        super(jwtToken);
        this.email = email;
        this.name = name;
        this.picturePath = picturePath;
        this.location = location;
        this.bio = bio;
        this.birthDate = birthDate;
        this.creationDate = creationDate;
    }
}