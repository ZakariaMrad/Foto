import { ref } from "vue";
const bus = ref(new Map());


export enum Events {
LOGIN = 'LOGIN',
LOGOUT = 'LOGOUT',
CONNECTED_ACCOUNT = 'CONNECTED_ACCOUNT',
CREATE_POST = 'CREATE_POST',
OPEN_SEARCH_MODAL = 'OPEN_SEARCH_MODAL',
CREATE_ALBUM = "CREATE_ALBUM"
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