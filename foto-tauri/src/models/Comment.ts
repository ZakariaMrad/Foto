import { APIResponse } from "../core/API/APIResponse";
import { JWTToken } from "./JWTToken";
import Account from "./Account";
import Post from "./Post";

export default class Comment extends JWTToken implements APIResponse{
    idComment: number = 0;
    user: Account = new Account;
    post: Post = new Post;
    text: string = '';
    declare message?: string | undefined;
}