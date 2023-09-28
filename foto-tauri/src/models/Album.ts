import { APIResponse } from "../core/API/APIResponse";

export default class Album implements APIResponse{
    idAlbym: number = 0;
    name: string = '';
    description: string = '';
    message?: string | undefined;
}