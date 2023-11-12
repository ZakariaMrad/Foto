import Account from "./Account";

export default class UserBlock {
    idUserBlock: number = 0;
    account : Account = new Account();
    reason: string = '';
    blockDateTime: Date = new Date();
}