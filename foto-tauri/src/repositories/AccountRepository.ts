import { inject } from 'vue'
import { AxiosStatic } from 'axios'
import { Account } from '../models/Account'

export class AccountRepository{
    private axios = inject<AxiosStatic>('axios');



}