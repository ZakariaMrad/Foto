import { APIError } from "./APIError";

// Define a type for a successful result
type Success<T> = {
    data: T;
    success: true;
};

// Define a type for an error
type Error = {
    errors: APIError[] ; // You can customize the error type to your needs
    success: false;
};

// Define a union type that can be either Success or Error
export type APIResult<T> = Success<T> | Error;
