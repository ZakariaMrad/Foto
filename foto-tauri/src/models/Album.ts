import { APIResponse } from "../core/API/APIResponse";
import Foto from "./Foto";

export default class Album implements APIResponse{
    idAlbum: number = 0;
    title: string = '';
    description: string = '';
    notes: string = '';
    fotos: Foto[] = [];
    message?: string | undefined;
}