import { LoginAccount } from "./LoginAccount";

export class Account extends LoginAccount{
    public id: number = 0;
    public name: string = '';
    public jwtToken: string = '';
}