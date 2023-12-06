import { Body, ResponseType, getClient } from "@tauri-apps/api/http";
import { Repository } from "./Repository";
import Complaint from "../models/Complaint";
import { APIError } from "../core/API/APIError";
import { JWTToken } from "../models/JWTToken";
import ComplaintPost from "../models/ComplaintPost";
import AccountRepository from "./AccountRepository";

const client = await getClient();

class ComplaintRepository extends Repository {




    public async createComplaint(idSubject: number, type: string, idRecipient: number) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };
        let complaintPost = new ComplaintPost();
        complaintPost.jwtToken = jwt.data.jwtToken;
        complaintPost.idSubject = idSubject;
        complaintPost.subjectType = type;
        complaintPost.idRecipient = idRecipient;

        let account = await AccountRepository.getAccount();
        if (!account.success) return { errors: account.errors, success: false };
        complaintPost.idSender = account.data.idAccount;

        try {
            const response = await client.post(`${this.url}/complaint`, Body.json(complaintPost), { responseType: ResponseType.JSON });
            let data = response.data as JWTToken;
            console.log(response.data);

            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);

                return { data: data, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };

        } catch (error) {
            // Handle any network or request-related errors here and return an Error object
            console.log(error);

            return { errors: error as [APIError], success: false };
        }
    }

    public async getActiveComplaints() {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.get(`${this.url}/complaints/active?jwtToken=${jwt.data.jwtToken}`, { responseType: ResponseType.JSON });
            let data = response.data as any;
            console.log(data);
            
            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                return { data: data.complaints as Complaint[], success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }
    
    public async getArchivedComplaints() {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.get(`${this.url}/complaints/archived?jwtToken=${jwt.data.jwtToken}`, { responseType: ResponseType.JSON });
            let data = response.data as any;
        
            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                return { data: data.complaints as Complaint[], success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }
    
    public async deleteComplaintSubject(idComplant: number) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.delete(`${this.url}/complaint/${idComplant}/deleteSubject?jwtToken=${jwt.data.jwtToken}`, { responseType: ResponseType.JSON });
            let data = response.data as any;
            console.log(data);
            
            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                return { data: data.message, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }
    public async archiveComplaint(idComplant: number) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.post(`${this.url}/complaint/${idComplant}/archive`,Body.json({"jwtToken": jwt.data.jwtToken}), { responseType: ResponseType.JSON });
            let data = response.data as any;
            console.log(data);
            
            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                return { data: data.message, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }
    public async activateComplaint(idComplant: number) {
        let jwt = await this.getJWTToken();
        if (!jwt.success) return { errors: jwt.errors, success: false };

        try {
            const response = await client.post(`${this.url}/complaint/${idComplant}/activate`,Body.json({"jwtToken": jwt.data.jwtToken}), { responseType: ResponseType.JSON });
            let data = response.data as any;
            console.log(data);
            
            if (response.status === 200) {
                this.handleJWT(response.data as JWTToken);
                return { data: data.message, success: true };
            }
            // If there is an unexpected response or error status code, return an Error object
            return { errors: this.getAPIError(response.data), success: false };
        } catch (error) {
            return { errors: error as [APIError], success: false };
        }
    }
}
export default new ComplaintRepository();