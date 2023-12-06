import { APIResponse } from "../core/API/APIResponse";
import Account from "./Account";
import Album from "./Album";
import Foto from "./Foto";
import Like from "./Like";
import Comment from "./Comment";
import { JWTToken } from "./JWTToken";

export default class Post extends JWTToken implements APIResponse{
    idPost: number = 0;
    owner: Account = new Account();
    description: string = '';
    creationDate= {date: ""}
    foto:Foto = new Foto();
    album:Album = new Album();
    likes: Like[] = [];
    isLiked: boolean = false;

    isPublic: boolean = false;

    comments: Comment[] = [];
}