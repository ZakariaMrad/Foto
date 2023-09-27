export class APIError {
    propertyName: string = '';
    message: string = '';
    isPositive: boolean = false;
    public constructor(propertyName: string, errorMessage: string, isPositive: boolean = false) {
        this.propertyName = propertyName;
        this.message = errorMessage;
        this.isPositive = isPositive;
    }
}