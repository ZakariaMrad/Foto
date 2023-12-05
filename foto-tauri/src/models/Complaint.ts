import Account from "./Account";
import ComplaintSubject from "./ComplaintSubject";


export default class Complain {
    idComplaint: Number = 0;
    subject: ComplaintSubject = new ComplaintSubject();
    sentDateTime = {date: ""};
    readDateTime: Date | undefined = undefined;
    status: string = '';
    sender: Account = new Account();
    recipient: Account = new Account();

}
