import { ref } from "vue";
const bus = ref(new Map());

export enum Events {
    test = 'test',
    LOGIN='LOGIN',
    LOGOUT='LOGOUT',
    CONNECTED_ACCOUNT='CONNECTED_ACCOUNT',
    CREATE_POST='CREATE_POST', 
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