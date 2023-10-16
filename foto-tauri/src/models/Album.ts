import { APIResponse } from "../core/API/APIResponse";
import AlbumGrid from "./AlbumGrid";
import Foto from "./Foto";
import Account from "./Account";
import { JWTToken } from "./JWTToken";

export default class Album extends JWTToken implements APIResponse {
    idAlbum: number = 0;
    title: string = '';
    description: string = '';
    notes: string = '';
    fotos: Foto[] = [];
    type: 'grid' | 'carousel' = 'grid';
    grid: AlbumGrid = new AlbumGrid();
    collaboraters: Account[] = [];
    spectators: Account[] = [];
}