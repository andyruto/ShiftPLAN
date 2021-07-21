/**
 * logintworesponse.ts
 * 
 * Interface to receive the response for a post request.
 * 
 * author: Sascha W.
 * last edit / by: 2021-06-29 / Sascha W.
 */

import { GeneralResponse } from 'src/app/models/generalresponse';

 // response interface to receive a response for the second login post request
export interface LoginTwoResponse extends GeneralResponse  {
   session: string;
   nonce: string;
}