import AccountRepository from '../repositories/AccountRepository';
import { LoginAccount } from '../models/LoginAccount';

export const successFn = (form: any) => {
//do anything with form
let a = await AccountRepository.login(form as LoginAccount);
console.log(
a
);
console.log("success");
};
