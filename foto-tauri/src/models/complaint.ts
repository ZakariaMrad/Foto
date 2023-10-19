export default class Complaint {
    idComplaint: number = 0;
    idUser: number = 0;
    state: 'unread' | 'resolved' | 'unresolved' = 'unread';
    title: string = '';
    description: string = '';

    public constructor(idComplaint:number, idUser:number, state:'unread' | 'resolved' | 'unresolved', title:string, description:string){
        this.idComplaint = idComplaint;
        this.idUser = idUser;
        this.state = state;
        this.title = title;
        this.description = description;

    }
}

