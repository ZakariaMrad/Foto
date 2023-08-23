import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'foto.capacitor',
  appName: 'foto-capacitor',
  webDir: 'dist',
  server: {
    androidScheme: 'https'
  }
};

export default config;
