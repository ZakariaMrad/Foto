import { Store } from "tauri-plugin-store-api";
import { JWTToken } from "../../models/JWTToken";

const accountStore = new Store('./account.dat')

class AccountDatastore {
    public async removeJWTToken() {
        await accountStore.delete('jwt_token');
        await accountStore.save();
    }

    public async setJWTToken(jwtToken: JWTToken) {
        try {           
            await accountStore.set('jwt_token', jwtToken);
            await accountStore.save();
        } catch (error) {
            console.log(error);
        }

    }
    public async getJWTToken(): Promise<JWTToken | undefined> {
        let jwtToken = await accountStore.get<string>('jwt_token');
        if (!jwtToken) return undefined;
        return new JWTToken(jwtToken);
    }
}
export default new AccountDatastore();