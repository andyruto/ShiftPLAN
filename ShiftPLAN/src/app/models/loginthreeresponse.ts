/**
 * loginthreeresponse.ts
 * 
 * Interface to receive the response for a post request.
 * 
 * author: Sascha W.
 * last edit / by: 2021-06-30 / Sascha W.
 */

 import { GeneralResponse } from 'src/app/models/generalresponse';

 // response interface to receive a response for the third login post request
export interface LoginThreeResponse extends GeneralResponse  {
   userType: number
}