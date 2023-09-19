import { APIError } from "../core/API/APIError";

export class Repository {
    public getAPIError(errors:any): APIError[] {
        let keys = Object.keys(errors);
        let errorsArray: APIError[] = [];
        keys.forEach(key => {

            errorsArray.push(new APIError(key, errors[key][0], key=='message'));
        });
        return errorsArray;
    }


}