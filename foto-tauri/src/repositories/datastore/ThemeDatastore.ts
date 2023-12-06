import { Store } from "tauri-plugin-store-api";

const themeStore = new Store('./theme.dat')

class ThemeDatastore {

    public async setTheme(theme: string) {
        try {
            await themeStore.set('theme', theme);
            await themeStore.save();
        } catch (error) {
            console.log(error);
        }

    }

    public async getTheme(): Promise<string | undefined> {
        let theme = await themeStore.get<string>('theme');
        if (!theme) return undefined;
        return theme;
    }


}
export default new ThemeDatastore();