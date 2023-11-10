import { JWTToken } from "./JWTToken";

export default class UserBlockPost extends JWTToken {
    idUser: number = 0;
    reason: string = '';
}