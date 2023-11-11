import { APIResponse } from "../core/API/APIResponse";
import { JWTToken } from "./JWTToken";

export default class ComplaintPost extends JWTToken implements APIResponse {
    idSubject: number = 0;
    subjectType: string = '';
    idSender: number = 0;
    idRecipient: number = 0;
}
