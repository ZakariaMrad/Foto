export default class RegistraionAccount{
    name: string="";
    email: string="";
    password:{
        first: string;
        second: string;
    }={
        first: "",
        second: ""
    };
    location: string="";
    birthDate: string="";
    
    constructor(name: string, location: string, birthDate: string, email: string, passwordFirst: string, passwordSecond:string){
        this.name = name;
        this.location = location;
        this.birthDate = birthDate;
        this.email = email;
        this.password.first = passwordFirst;
        this.password.second = passwordSecond;
    }
}