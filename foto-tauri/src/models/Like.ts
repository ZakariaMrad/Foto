import { APIResponse } from "../core/API/APIResponse";
import { JWTToken } from "./JWTToken";
import Account from "./Account";
import Post from "./Post";

export default class Like extends JWTToken implements APIResponse{
    idLike: number = 0;
    user: Account = new Account;
    post: Post = new Post;
    declare message?: string | undefined;
}