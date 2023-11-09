import { ref } from "vue";
const bus = ref(new Map());


export enum Events {
    LOGIN='LOGIN',
    LOGOUT='LOGOUT',
    CONNECTED_ACCOUNT='CONNECTED_ACCOUNT',
    CREATE_POST='CREATE_POST',
    OPEN_SEARCH_MODAL='OPEN_SEARCH_MODAL',
    OPEN_EDIT_MODAL='OPEN_EDIT_MODAL',
    CREATE_ALBUM = "CREATE_ALBUM",
    RELOAD_CONNECTED_ACCOUNT = "RELOAD_CONNECTED_ACCOUNT",
    OPEN_MODIFY_PROFILE_MODAL='OPEN_MODIFY_PROFILE_MODAL',
    RELOAD_POSTS = "RELOAD_POSTS",
    OPEN_ADMIN_PANEL = 'OPEN_ADMIN_PANEL',
    GET_OTHER_USER_PROFILE = 'GET_OTHER_USER_PROFILE',
    OPEN_USER_PROFILE = 'OPEN_USER_PROFILE'
};

export function EventsBus(){

    function eventBusEmit(event:string, ...args:any) {
        bus.value.set(event, args);
    }
 
    return {
        eventBusEmit,  
        bus
    }
};