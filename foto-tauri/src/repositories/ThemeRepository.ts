import { APIResult } from "../core/API/APIResult";
import ThemeDatastore from "./datastore/ThemeDatastore";


class ThemeRepository {

    public async setTheme(theme: string): Promise<APIResult<undefined>> {
        await ThemeDatastore.setTheme(theme);
        console.log('set theme', theme);
        
        return { success: true, data: undefined };
    }
    public async getTheme(): Promise<APIResult<string>> {
        
        let theme = await ThemeDatastore.getTheme();
        console.log('get theme', theme);
        return { success: true, data: theme || 'light' };
    }
}
export default new ThemeRepository();