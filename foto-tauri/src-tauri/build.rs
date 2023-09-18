// #![cfg_attr(not(debug_assertions), windows_subsystem = "windows")]

fn main() {
    tauri_build::build()
        // .run(tauri::generate_context!())
        // .expect("error while running tauri application");
}
